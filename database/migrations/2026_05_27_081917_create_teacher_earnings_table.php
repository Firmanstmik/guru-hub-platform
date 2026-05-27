<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->decimal('amount_earned', 10, 2); // Nominal pendapatan bersih guru
            $table->enum('status', ['unpaid', 'withdrawn'])->default('unpaid'); // Status penarikan saldo oleh guru
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_earnings');
    }
};
