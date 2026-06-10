<?php

namespace App\Http\Controllers;

use App\Services\JntTrackingService;
use Illuminate\Http\Request;
use RuntimeException;

class TrackingController extends Controller
{
    public function __construct(private readonly JntTrackingService $jntService)
    {
    }

    public function index()
    {
        return view('tracking.index', [
            'isConfigured' => $this->jntService->isConfigured(),
            'couriers' => config('services.binderbyte.couriers', []),
            'selectedCourier' => config('services.binderbyte.courier', 'jnt'),
        ]);
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'awb' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9-]+$/'],
            'courier' => ['required', 'string', 'in:'.implode(',', array_keys(config('services.binderbyte.couriers', [])))],
        ], [
            'awb.regex' => 'Nomor resi hanya boleh berisi huruf, angka, dan tanda hubung.',
            'courier.in' => 'Kurir yang dipilih belum tersedia.',
        ]);

        try {
            $trackingData = $this->jntService->track($validated['awb'], $validated['courier']);
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        if ($trackingData === null) {
            return back()
                ->withInput()
                ->with('error', 'Gagal melacak paket. Periksa nomor resi atau coba lagi.');
        }

        return view('tracking.index', [
            'awb' => $validated['awb'],
            'couriers' => config('services.binderbyte.couriers', []),
            'selectedCourier' => $validated['courier'],
            'trackingData' => $trackingData,
            'isConfigured' => true,
        ]);
    }
}
