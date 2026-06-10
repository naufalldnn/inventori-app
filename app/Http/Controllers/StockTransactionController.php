<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class StockTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = StockTransaction::with(['item.category', 'user'])
            ->when($request->type, fn ($query, $type) => $query->where('type', $type))
            ->when($request->date_from, fn ($query, $date) => $query->whereDate('transaction_date', '>=', $date))
            ->when($request->date_to, fn ($query, $date) => $query->whereDate('transaction_date', '<=', $date))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function create(string $type): View
    {
        abort_unless(in_array($type, [StockTransaction::TYPE_IN, StockTransaction::TYPE_OUT], true), 404);

        return view('transactions.form', [
            'type' => $type,
            'items' => Item::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, InventoryService $service): RedirectResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $service->record(Item::findOrFail($data['item_id']), $request->user(), $data['type'], $data['quantity'], $data['transaction_date'], $data['notes'] ?? null);
        } catch (InvalidArgumentException $exception) {
            return back()->withErrors(['quantity' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }
}
