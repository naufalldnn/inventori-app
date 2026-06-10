<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    public function record(Item $item, User $user, string $type, int $quantity, string $date, ?string $notes): StockTransaction
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Jumlah transaksi harus lebih dari nol.');
        }

        return DB::transaction(function () use ($item, $user, $type, $quantity, $date, $notes) {
            $item = Item::query()->whereKey($item->id)->lockForUpdate()->firstOrFail();

            if ($type === StockTransaction::TYPE_OUT && $item->stock < $quantity) {
                throw new InvalidArgumentException('Stok barang tidak mencukupi.');
            }

            $item->stock += $type === StockTransaction::TYPE_IN ? $quantity : -$quantity;
            $item->save();

            $transaction = StockTransaction::create([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'type' => $type,
                'quantity' => $quantity,
                'transaction_date' => $date,
                'notes' => $notes,
            ]);

            $this->notifyTransaction($user, $transaction);
            $this->notifyStockStatus($item);

            return $transaction;
        });
    }

    private function notifyTransaction(User $user, StockTransaction $transaction): void
    {
        AppNotification::create([
            'user_id' => $user->id,
            'title' => 'Transaksi berhasil',
            'message' => 'Transaksi '.$transaction->item->name.' berhasil dicatat.',
            'type' => 'success',
        ]);
    }

    private function notifyStockStatus(Item $item): void
    {
        if ($item->stock > $item->minimum_stock) {
            return;
        }

        $type = $item->stock <= 0 ? 'danger' : 'warning';
        $title = $item->stock <= 0 ? 'Stok habis' : 'Stok menipis';

        User::query()->where('role', 'admin')->each(function (User $admin) use ($item, $title, $type) {
            AppNotification::create([
                'user_id' => $admin->id,
                'title' => $title,
                'message' => $item->name.' tersisa '.$item->stock.' '.$item->unit.'.',
                'type' => $type,
            ]);
        });
    }
}
