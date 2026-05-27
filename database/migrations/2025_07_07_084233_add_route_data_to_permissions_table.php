<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('controller')->nullable();
            $table->string('uri')->nullable();
            $table->string('method')->nullable();
            $table->string('action')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
        if (Schema::hasColumn('permissions', 'controller')) {
            $table->dropColumn('controller');
        }
        if (Schema::hasColumn('permissions', 'uri')) {
            $table->dropColumn('uri');
        }
        if (Schema::hasColumn('permissions', 'method')) {
            $table->dropColumn('method');
        }
        if (Schema::hasColumn('permissions', 'action')) {
            $table->dropColumn('action');
        }
    });
    }
};
