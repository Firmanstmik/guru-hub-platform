<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('invoice_number')->unique(); // Contoh: INV-202605-001
            $table->decimal('amount', 10, 2);
            $table->string('payment_proof_path'); // Path file foto bukti transfer manual
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Diisi User ID Admin yang verifikasi
            $table->text('rejection_reason')->nullable(); // Catatan jika admin menolak bukti transfer siswa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
