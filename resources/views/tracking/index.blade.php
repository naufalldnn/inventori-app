@extends('layouts.app')

@section('title', 'Tracking Pengiriman')

@section('content')
    <div class="mx-auto max-w-3xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-ink">Lacak Paket</h2>
        <p class="mt-1 text-sm text-gray-600">Masukkan nomor resi untuk melihat status pengiriman.</p>

        @unless ($isConfigured)
            <div class="mt-4 rounded border border-amber/20 bg-amber/10 px-4 py-3 text-sm text-amber">
                API Binderbyte belum aktif. Isi BINDERBYTE_API_KEY untuk mengaktifkan tracking pengiriman.
            </div>
        @endunless

        <form action="{{ route('tracking.track') }}" method="POST" class="mt-5 flex flex-col gap-3 sm:flex-row">
            @csrf
            <select
                name="courier"
                class="rounded border border-gray-300 px-3 py-2 focus:border-ink focus:outline-none"
                required
            >
                @foreach ($couriers as $courierCode => $courierName)
                    <option value="{{ $courierCode }}" @selected(old('courier', $selectedCourier ?? 'jnt') === $courierCode)>
                        {{ $courierName }}
                    </option>
                @endforeach
            </select>
            <input
                type="text"
                name="awb"
                value="{{ old('awb', $awb ?? '') }}"
                maxlength="30"
                class="flex-1 rounded border border-gray-300 px-3 py-2 uppercase focus:border-ink focus:outline-none"
                placeholder="Nomor resi"
                required
            >
            <button type="submit" class="rounded bg-moss px-5 py-2 font-semibold text-white hover:bg-moss/90">
                Lacak
            </button>
        </form>
    </div>

    @isset($trackingData)
        <section class="mx-auto mt-6 max-w-3xl rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <p class="text-sm text-gray-500">Hasil Pelacakan</p>
                <h2 class="mt-1 font-mono text-lg font-bold text-ink">{{ $awb }}</h2>
            </div>

            <div class="grid gap-4 border-b border-gray-200 p-6 sm:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Kurir</p>
                    <p class="mt-1 font-semibold text-ink">{{ data_get($trackingData, 'summary.courier') ?: data_get($couriers, $selectedCourier) ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status</p>
                    <p class="mt-1 font-semibold text-ink">{{ data_get($trackingData, 'summary.status') ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Penerima</p>
                    <p class="mt-1 font-semibold text-ink">{{ data_get($trackingData, 'summary.receiver') ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Resi</p>
                    <p class="mt-1 font-mono font-semibold text-ink">{{ data_get($trackingData, 'summary.awb') ?: $awb }}</p>
                </div>
            </div>

            <div class="p-6">
                <h3 class="font-bold text-ink">Riwayat</h3>
                <div class="mt-4 space-y-3">
                    @forelse (data_get($trackingData, 'history', []) as $history)
                        <div class="rounded border border-gray-200 p-4">
                            <p class="text-sm font-semibold text-ink">
                                {{ data_get($history, 'date') ?? data_get($history, 'time') ?? data_get($history, 'datetime') ?? '-' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ data_get($history, 'description') ?? data_get($history, 'status') ?? data_get($history, 'desc') ?? json_encode($history) }}
                            </p>
                        </div>
                    @empty
                        <p class="rounded border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                            Riwayat tracking belum tersedia dari respons Binderbyte.
                        </p>
                    @endforelse
                </div>
            </div>
        </section>
    @endisset
@endsection
