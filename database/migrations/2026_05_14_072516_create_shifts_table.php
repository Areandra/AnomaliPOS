<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['open', 'closed']);
            $table->decimal('modal_awal', 12, 2);
            $table->timestamp('opened_at');
            $table->decimal('cash_system', 12, 2);
            $table->decimal('cash_physical', 12, 2);
            $table->decimal('cash_variance', 12, 2);
            $table->decimal('qris_system', 12, 2);
            $table->decimal('debit_system', 12, 2);
            $table->decimal('transfer_system', 12, 2);
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
