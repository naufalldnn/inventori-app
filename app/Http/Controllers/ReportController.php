<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function stock(): View
    {
        return view('reports.stock', ['items' => Item::with('category')->orderBy('name')->get()]);
    }

    public function stockPdf()
    {
        $pdf = Pdf::loadView('reports.pdf.stock', ['items' => Item::with('category')->orderBy('name')->get()]);
        return $pdf->download('laporan-stok-barang.pdf');
    }

    public function transactions(Request $request, string $type): View
    {
        abort_unless(in_array($type, ['in', 'out'], true), 404);

        return view('reports.transactions', [
            'type' => $type,
            'transactions' => $this->transactionQuery($request, $type)->get(),
        ]);
    }

    public function transactionsPdf(Request $request, string $type)
    {
        abort_unless(in_array($type, ['in', 'out'], true), 404);

        $pdf = Pdf::loadView('reports.pdf.transactions', [
            'type' => $type,
            'transactions' => $this->transactionQuery($request, $type)->get(),
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ]);

        return $pdf->download('laporan-barang-'.($type === 'in' ? 'masuk' : 'keluar').'.pdf');
    }

    private function transactionQuery(Request $request, string $type)
    {
        return StockTransaction::with(['item.category', 'user'])
            ->where('type', $type)
            ->when($request->date_from, fn ($query, $date) => $query->whereDate('transaction_date', '>=', $date))
            ->when($request->date_to, fn ($query, $date) => $query->whereDate('transaction_date', '<=', $date))
            ->orderByDesc('transaction_date');
    }
}
