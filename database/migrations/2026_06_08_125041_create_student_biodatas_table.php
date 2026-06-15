<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_biodatas', function (Blueprint $blueprint) {
            $blueprint->id();
            
            $blueprint->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
                
            // Field opsional tambahan untuk biodata siswa Guru Hub
            $blueprint->string('nisn', 20)->nullable()->unique();
            $blueprint->string('institution_name')->nullable(); // Sekolah/Kampus
            $blueprint->date('birth_date')->nullable();
            $blueprint->enum('gender', ['L', 'P'])->nullable();
            $blueprint->text('address')->nullable();
            $blueprint->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_biodatas');
    }
};
