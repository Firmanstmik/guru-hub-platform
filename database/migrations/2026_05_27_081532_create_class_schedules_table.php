<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('topic'); // Contoh: "Sesi 1: Pengenalan Hiragana"
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('platform', ['zoom', 'google_meet'])->nullable();
            $table->text('meeting_link')->nullable(); // Diisi link otomatis lewat API Zoom/GMeet atau manual oleh guru
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
