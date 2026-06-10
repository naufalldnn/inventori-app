<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin Inventori', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            ['name' => 'Petugas Gudang', 'password' => Hash::make('password'), 'role' => 'petugas']
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'User Inventori', 'password' => Hash::make('password'), 'role' => 'user']
        );

        $categories = collect(['Elektronik', 'ATK', 'Kebersihan', 'Peralatan'])
            ->map(fn (string $name) => Category::updateOrCreate(['name' => $name], ['description' => 'Kategori '.$name]));

        $sampleItems = [
            ['Elektronik', 'BRG-EL-001', 'Router WiFi', 'unit', 250000, 12, 4],
            ['ATK', 'BRG-ATK-001', 'Kertas A4', 'rim', 55000, 30, 10],
            ['Kebersihan', 'BRG-KBR-001', 'Sabun Lantai', 'botol', 18000, 6, 8],
            ['Peralatan', 'BRG-PRL-001', 'Obeng Set', 'set', 75000, 0, 3],
        ];

        foreach ($sampleItems as [$categoryName, $code, $name, $unit, $price, $stock, $minimum]) {
            Item::updateOrCreate(
                ['code' => $code],
                [
                    'category_id' => $categories->firstWhere('name', $categoryName)->id,
                    'name' => $name,
                    'unit' => $unit,
                    'price' => $price,
                    'stock' => $stock,
                    'minimum_stock' => $minimum,
                    'description' => 'Data awal '.$name,
                ]
            );
        }

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => min($admin->id, $petugas->id),
            'user_two_id' => max($admin->id, $petugas->id),
        ]);

        Message::firstOrCreate([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'body' => 'Selamat datang di chat internal inventori.',
        ]);
    }
}
