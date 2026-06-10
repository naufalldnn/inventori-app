<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            $orderSummary = Order::where('user_id', $user->id)
                ->selectRaw('count(*) as total')
                ->selectRaw("sum(case when status = 'paid' then 1 else 0 end) as paid")
                ->selectRaw("sum(case when status = 'pending' then 1 else 0 end) as pending")
                ->first();

            return view('user.dashboard', [
                'orderSummary' => $orderSummary,
                'orders' => Order::with('item')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->paginate(10),
                'items' => Item::with('category')
                    ->where('stock', '>', 0)
                    ->latest()
                    ->limit(8)
                    ->get(),
            ]);
        }

        return view('dashboard', [
            'itemCount' => Item::count(),
            'categoryCount' => Category::count(),
            'lowStockCount' => Item::whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0)->count(),
            'emptyStockCount' => Item::where('stock', '<=', 0)->count(),
            'recentTransactions' => StockTransaction::with(['item', 'user'])->latest()->limit(8)->get(),
            'lowItems' => Item::with('category')->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->limit(8)->get(),
        ]);
    }
}
