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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('table_number');
            $table->integer('capacity');
            $table->integer('position_x')->nullable();
            $table->integer('position_y')->nullable();
            $table->boolean('facing')->default(false);
            $table->boolean('vertical')->default(false);
            $table->enum('status', ['available', 'occupied', 'waiting_payment'])->default('available');
            $table->unsignedBigInteger('current_table_session_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
