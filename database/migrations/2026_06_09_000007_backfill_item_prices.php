<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('items', 'price')) {
            return;
        }

        DB::table('items')
            ->whereNull('price')
            ->orWhere('price', '<', 1000)
            ->update(['price' => 10000]);
    }

    public function down(): void
    {
        //
    }
};
