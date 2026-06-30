<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_biodatas', function (Blueprint $table) {
            $table->foreignId('education_level_id')
                ->nullable()
                ->after('institution_name')
                ->constrained('education_levels')
                ->nullOnDelete();
        });

        Schema::create('teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject');

        Schema::table('student_biodatas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('education_level_id');
        });
    }
};
