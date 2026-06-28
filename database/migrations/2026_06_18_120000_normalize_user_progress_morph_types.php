<?php

use App\Support\ProgressMorphType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeMorphType('App\Models\Video', ProgressMorphType::VIDEO);
        $this->normalizeMorphType('App\Models\Material', ProgressMorphType::MATERIAL);
    }

    public function down(): void
    {
        $this->normalizeMorphType(ProgressMorphType::VIDEO, 'App\Models\Video');
        $this->normalizeMorphType(ProgressMorphType::MATERIAL, 'App\Models\Material');
    }

    private function normalizeMorphType(string $fromType, string $toType): void
    {
        $legacyRows = DB::table('user_progress')
            ->where('progressable_type', $fromType)
            ->get();

        foreach ($legacyRows as $row) {
            $duplicateExists = DB::table('user_progress')
                ->where('user_id', $row->user_id)
                ->where('progressable_id', $row->progressable_id)
                ->where('progressable_type', $toType)
                ->exists();

            if ($duplicateExists) {
                DB::table('user_progress')->where('id', $row->id)->delete();
                continue;
            }

            DB::table('user_progress')
                ->where('id', $row->id)
                ->update(['progressable_type' => $toType]);
        }
    }
};
