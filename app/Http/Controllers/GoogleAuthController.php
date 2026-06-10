<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Client\RequestException;
use RuntimeException;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        try {
            $clientId = $this->clientId();
        } catch (RuntimeException $exception) {
            return redirect()->route('login')->withErrors(['email' => $exception->getMessage()]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google dibatalkan.']);
        }

        if (! $request->filled('code')) {
            return redirect()->route('login')->withErrors(['email' => 'Kode OAuth Google tidak ditemukan.']);
        }

        $expectedState = (string) $request->session()->pull('google_oauth_state');

        if (! $request->filled('state') || ! hash_equals($expectedState, (string) $request->state)) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi login Google tidak valid. Silakan coba lagi.']);
        }

        try {
            $token = Http::asForm()
                ->post('https://oauth2.googleapis.com/token', [
                    'client_id' => $this->clientId(),
                    'client_secret' => $this->clientSecret(),
                    'code' => (string) $request->string('code'),
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $this->redirectUri(),
                ])
                ->throw()
                ->json();

            if (! isset($token['access_token'])) {
                return redirect()->route('login')->withErrors(['email' => 'Token OAuth Google tidak valid.']);
            }

            $profile = Http::withToken($token['access_token'])
                ->get('https://www.googleapis.com/oauth2/v3/userinfo')
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Periksa konfigurasi Client ID, Client Secret, dan redirect URI.']);
        } catch (RuntimeException $exception) {
            return redirect()->route('login')->withErrors(['email' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        if (! isset($profile['sub'], $profile['email'], $profile['name'])) {
            return redirect()->route('login')->withErrors(['email' => 'Profil Google tidak lengkap.']);
        }

        if (! ($profile['email_verified'] ?? false)) {
            return redirect()->route('login')->withErrors(['email' => 'Email Google belum terverifikasi.']);
        }

        $user = User::where('google_id', $profile['sub'])
            ->orWhere('email', $profile['email'])
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $profile['sub'],
                'name' => $user->name ?: $profile['name'],
                'avatar' => $profile['picture'] ?? $user->avatar,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'google_id' => $profile['sub'],
                'name' => $profile['name'],
                'email' => $profile['email'],
                'email_verified_at' => now(),
                'avatar' => $profile['picture'] ?? null,
                'password' => Hash::make(Str::random(32)),
                'role' => 'user',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function clientId(): string
    {
        return $this->requiredConfig('client_id');
    }

    private function clientSecret(): string
    {
        return $this->requiredConfig('client_secret');
    }

    private function redirectUri(): string
    {
        $value = config('services.google.redirect_uri');

        return is_string($value) && $value !== ''
            ? $value
            : route('auth.google.callback');
    }

    private function requiredConfig(string $key): string
    {
        $value = config("services.google.{$key}");

        if (! is_string($value) || $value === '') {
            throw new RuntimeException("Konfigurasi Google OAuth {$key} belum diisi.");
        }

        return $value;
    }
}
