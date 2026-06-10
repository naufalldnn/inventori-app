<?php

namespace App\Http\Controllers;

use App\Models\Item;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'items' => Item::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
