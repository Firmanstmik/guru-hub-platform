<?php

namespace App\Providers;

use App\Models\Categori;
use App\Models\EducationLevel;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->register(PermissionRouteServiceProvider::class);

        $this->registerBrowseRouteBindings();
    }

    private function registerBrowseRouteBindings(): void
    {
        Route::bind('level', function (string $value, $route) {
            $category = $route->parameter('category');

            if (! $category instanceof Categori) {
                return EducationLevel::query()
                    ->where('slug', $value)
                    ->firstOrFail();
            }

            return EducationLevel::query()
                ->active()
                ->where('slug', $value)
                ->whereHas('subjects', fn ($q) => $q->active()->where('category_id', $category->id))
                ->firstOrFail();
        });

        Route::bind('subject', function (string $value, $route) {
            $category = $route->parameter('category');
            $level = $route->parameter('level');

            if (! $category instanceof Categori || ! $level instanceof EducationLevel) {
                return Subject::query()
                    ->where('slug', $value)
                    ->firstOrFail();
            }

            return Subject::query()
                ->active()
                ->where('slug', $value)
                ->where('category_id', $category->id)
                ->where('education_level_id', $level->id)
                ->firstOrFail();
        });
    }
}
