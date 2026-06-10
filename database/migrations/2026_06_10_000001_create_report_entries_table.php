<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type');
            $table->string('status')->default('draft');
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->text('summary');
            $table->timestamps();
            $table->index(['type', 'status']);
            $table->index(['period_from', 'period_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_entries');
    }
};
