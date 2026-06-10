<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('media_url')->nullable()->after('description');
            $table->string('media_public_id')->nullable()->after('media_url');
            $table->string('media_type')->nullable()->after('media_public_id');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['media_url', 'media_public_id', 'media_type']);
        });
    }
};
