<?php

namespace App\Providers;

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class PermissionRouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!app()->runningInConsole() && !app()->routesAreCached()) {
            $this->mapDynamicPermissionRoutes();
        }
    }

    protected function mapDynamicPermissionRoutes()
    {
        $permissions = Permission::whereNotNull('uri')
            ->whereNotNull('method')
            ->whereNotNull('action')
            ->whereNotNull('controller')
            ->get();

        foreach ($permissions as $permission) {
            $method = strtolower($permission->method);
            $allowedMethods = ['get', 'post', 'put', 'patch', 'delete'];

            if (!in_array($method, $allowedMethods)) {
                continue;
            }

            $controllerClass = "\\App\\Http\\Controllers\\{$permission->controller}";

            // 1. Tentukan susunan dasar middleware
            $middlewares = ['web', 'auth', "can:{$permission->name}"];

            // 2. KECUALIKAN rute form biodata agar tidak memicu redirect loop
            // Menghapus spasi/slash di ujung URI agar pencocokan teks lebih akurat
            $cleanUri = trim($permission->uri, '/');

            if ($cleanUri !== 'biodata' && $cleanUri !== 'teachers') {
                // Jika BUKAN rute pengisian profil, maka wajib pasang pengunci biodata
                $middlewares[] = 'auth.biodata';
            }

            // 3. Daftarkan rute dengan susunan middleware yang dinamis
            Route::group([
                'middleware' => $middlewares,
            ], function () use ($method, $permission, $controllerClass) {
                Route::match([$method], $permission->uri, [$controllerClass, $permission->action])
                    ->name("{$permission->controller}.{$permission->action}");
            });
        }
    }
}
