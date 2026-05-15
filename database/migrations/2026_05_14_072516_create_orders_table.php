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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained();
            $table->foreignId('restaurant_id')->constrained();
            $table->foreignId('table_session_id')->nullable()->constrained('table_sessions');
            // $table->integer('guest')->nullable();
            $table->string('order_code');
            $table->enum('type', ['dine_in', 'takeaway']);
            $table->enum('status', ['pending', 'cooking', 'served', 'completed', 'cancelled']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2);
            $table->decimal('discount', 12, 2);
            $table->decimal('total', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
