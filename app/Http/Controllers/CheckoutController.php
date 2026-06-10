<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Services\DokuPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected DokuPaymentService $dokuService;

    public function __construct(DokuPaymentService $dokuService)
    {
        $this->dokuService = $dokuService;
    }

    public function index()
    {
        $item = request('item_id')
            ? Item::with('category')->where('stock', '>', 0)->find(request('item_id'))
            : null;

        return view('checkout', compact('item'));
    }

    public function processCheckout(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required_without:item_id|nullable|numeric|min:1000|max:999999999',
                'description' => 'nullable|string|max:255',
                'item_id' => 'nullable|exists:items,id',
            ]);

            $user = Auth::user();
            $item = ! empty($validated['item_id'])
                ? Item::with('category')->where('stock', '>', 0)->find($validated['item_id'])
                : null;

            if (! empty($validated['item_id']) && ! $item) {
                return back()->with('error', 'Barang tidak tersedia atau stok habis.');
            }

            $amount = $item ? (float) $item->price : (float) $validated['amount'];

            if ($amount < 1000) {
                $message = $item
                    ? 'Harga barang belum valid. Ubah harga barang minimal Rp 1.000 sebelum checkout.'
                    : 'Jumlah pembayaran minimal Rp 1.000.';

                return back()->withInput()->with('error', $message);
            }

            $description = $validated['description'] ?? ($item ? 'Pesanan '.$item->name : null);

            // Create order
            $invoiceNumber = Order::generateInvoiceNumber();
            $order = Order::create([
                'user_id' => $user->id,
                'item_id' => $item?->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $amount,
                'description' => $description,
                'status' => 'pending',
                'payment_method' => 'doku',
            ]);

            Log::info('Order created', [
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $amount,
                'user_id' => $user->id,
            ]);

            if (! $this->dokuService->isConfigured()) {
                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Pesanan berhasil dibuat. Pembayaran online belum aktif karena konfigurasi Doku belum lengkap.');
            }

            // Generate Doku checkout URL
            $checkoutUrl = $this->dokuService->createCheckoutUrl(
                $invoiceNumber,
                $amount,
                $user->name,
                $user->email,
                route('doku.return', ['invoice_number' => $invoiceNumber]),
                $description,
                $item ? [array_filter([
                    'id' => (string) $item->id,
                    'name' => $item->name,
                    'quantity' => 1,
                    'price' => (int) round($amount),
                    'sku' => $item->code,
                    'category' => 'retail',
                    'type' => 'goods',
                    'url' => route('dashboard'),
                    'image_url' => $item->media_type === 'image' ? $item->media_url : null,
                ])] : []
            );

            $order->update(['payment_url' => $checkoutUrl]);

            // Redirect to Doku
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
        }
    }

    public function returnFromDoku(Request $request)
    {
        try {
            $invoiceNumber = $request->query('order_invoice_number')
                ?? $request->query('INVOICE_NUMBER')
                ?? $request->query('invoice_number');

            if (!$invoiceNumber) {
                return redirect()->route('dashboard')->with('error', 'Invoice number tidak ditemukan');
            }

            $order = Order::where('invoice_number', $invoiceNumber)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return redirect()->route('dashboard')->with('error', 'Order tidak ditemukan');
            }

            if ($status = $this->dokuService->queryTransactionStatus($order->invoice_number)) {
                $mappedStatus = $this->dokuService->mapStatusResponse($status);

                if ($mappedStatus && $mappedStatus !== $order->status) {
                    $order->update([
                        'status' => $mappedStatus,
                        'doku_response' => $status,
                        'paid_at' => $mappedStatus === 'paid' ? now() : $order->paid_at,
                    ]);
                }
            }

            // Status dapat diketahui dari callback, tapi kita bisa tambahkan query di sini untuk verifikasi
            $message = match ($order->status) {
                'paid' => 'Pembayaran berhasil! Terima kasih telah berbelanja.',
                'failed' => 'Pembayaran gagal. Silakan coba kembali.',
                'pending' => 'Pembayaran sedang diproses...',
                default => 'Status pembayaran tidak jelas',
            };

            $type = $order->isPaid() ? 'success' : ($order->isFailed() ? 'error' : 'info');

            return redirect()->route('dashboard')->with($type, $message);
        } catch (\Exception $e) {
            Log::error('Return from Doku error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan');
        }
    }
}
