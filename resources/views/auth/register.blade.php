@extends('layouts.app')

@section('content')
<div class="grid min-h-screen px-4 py-8 lg:grid-cols-[1fr_30rem] lg:px-0 lg:py-0">
    <section class="hidden border-r border-line bg-white px-10 py-12 lg:flex lg:flex-col lg:justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-lg bg-ocean text-sm font-black text-white">I6</span>
            <span>
                <span class="block text-lg font-black leading-tight">Inventori 6A1</span>
                <span class="block text-xs font-semibold text-slate-500">Katalog dan checkout</span>
            </span>
        </a>
        <div class="max-w-xl">
            <p class="w-fit rounded-full bg-teal-50 px-3 py-1 text-xs font-black uppercase tracking-wide text-moss">Akun pembeli</p>
            <h1 class="mt-5 text-5xl font-black leading-tight tracking-tight">Buat akun untuk pesan barang dari katalog.</h1>
            <p class="mt-5 text-base leading-7 text-slate-600">Setelah terdaftar, kamu bisa memilih barang, lanjut pembayaran DOKU, dan melihat riwayat pesanan.</p>
        </div>
        <div class="rounded-lg border border-line bg-cloud p-5">
            <p class="text-sm font-black uppercase tracking-wide text-slate-500">Alur cepat</p>
            <div class="mt-4 grid gap-3 text-sm font-semibold text-slate-700">
                <p>1. Daftar akun</p>
                <p>2. Pilih barang</p>
                <p>3. Checkout dan bayar</p>
            </div>
        </div>
    </section>

    <main class="grid place-items-center">
        <form method="post" action="{{ route('register') }}" class="w-full max-w-md rounded-lg border border-line bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <div class="mb-6">
                <p class="text-sm font-black uppercase tracking-wide text-moss">Mulai belanja</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">Daftar user</h1>
            </div>
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-coral">{{ $errors->first() }}</div>
            @endif
            <label class="mb-4 block text-sm font-bold text-slate-700">Nama
                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md">
            </label>
            <label class="mb-4 block text-sm font-bold text-slate-700">Email
                <input name="email" type="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-md">
            </label>
            <label class="mb-4 block text-sm font-bold text-slate-700">Password
                <input name="password" type="password" required class="mt-1 w-full rounded-md">
            </label>
            <label class="mb-6 block text-sm font-bold text-slate-700">Konfirmasi Password
                <input name="password_confirmation" type="password" required class="mt-1 w-full rounded-md">
            </label>
            <button class="btn-primary w-full">Daftar</button>
            <div class="my-4 flex items-center gap-3 text-xs font-semibold text-slate-400">
                <span class="h-px flex-1 bg-line"></span>
                <span>atau</span>
                <span class="h-px flex-1 bg-line"></span>
            </div>
            @if (config('services.google.client_id') && config('services.google.client_secret'))
                <a href="{{ route('auth.google.redirect') }}" class="btn-secondary w-full">
                    <span class="mr-2 grid h-5 w-5 place-items-center rounded-full border border-line bg-white text-xs font-black text-coral">G</span>
                    Daftar dengan Google
                </a>
            @else
                <div class="rounded-lg border border-line bg-cloud px-4 py-2 text-center text-sm text-slate-500">
                    Daftar Google belum aktif.
                </div>
            @endif
            <a href="{{ route('login') }}" class="mt-4 block text-center text-sm font-bold text-ocean hover:underline">Sudah punya akun</a>
        </form>
    </main>
</div>
@endsection
