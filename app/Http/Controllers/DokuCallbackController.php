<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DokuPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DokuCallbackController extends Controller
{
    protected DokuPaymentService $dokuService;

    public function __construct(DokuPaymentService $dokuService)
    {
        $this->dokuService = $dokuService;
    }

    public function handle(Request $request)
    {
        try {
            Log::info('Doku callback received', $request->all());

            // Handle callback
            $result = $this->dokuService->handleCallback($request->all());

            if (!$result['success']) {
                Log::error('Doku callback validation failed', $result);
                return response()->json(['status' => 'error', 'message' => $result['error']], 400);
            }

            // Find order
            $order = Order::where('invoice_number', $result['invoice_number'])->first();

            if (!$order) {
                Log::warning('Order not found for callback', ['invoice_number' => $result['invoice_number']]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Update order status
            $order->update([
                'status' => $result['status'],
                'doku_transaction_id' => $result['transaction_id'],
                'doku_response' => $result['response'],
                'paid_at' => $result['status'] === 'paid' ? now() : $order->paid_at,
            ]);

            Log::info('Order updated from callback', [
                'order_id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'status' => $result['status'],
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Callback processed',
                'invoice_number' => $result['invoice_number'],
                'order_status' => $result['status'],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Doku callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
