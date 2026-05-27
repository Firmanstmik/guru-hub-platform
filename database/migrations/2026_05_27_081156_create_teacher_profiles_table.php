<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable(); // Contoh: "Ex-Interpreter di Tokyo" atau "Lulusan N2"
            $table->text('bio')->nullable();
            $table->string('skills_tags')->nullable(); // Menyimpan teks tambahan (misal: "JLPT N3", "Tajwid", "Irama")
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('cv_file')->nullable(); // Dokumen pendukung untuk di-review Admin
            $table->decimal('average_rating', 3, 2)->default(0.00); // Untuk sorting "Guru Terfavorit"
            $table->string('bank_name')->nullable(); // Keperluan pencairan pendapatan guru
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
