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
        // Registered once via bootstrap/providers.php — do not double-register from AppServiceProvider.
        // Skip when routes are cached. Do NOT run `php artisan route:cache` with this provider.
        if (! app()->routesAreCached()) {
            $this->mapDynamicPermissionRoutes();
        }
    }

    protected function mapDynamicPermissionRoutes()
    {
        $permissions = cache()->remember('guruhub.permission_routes', 300, function () {
            return Permission::whereNotNull('uri')
                ->whereNotNull('method')
                ->whereNotNull('action')
                ->whereNotNull('controller')
                ->get(['id', 'name', 'uri', 'method', 'action', 'controller']);
        });

        foreach ($permissions as $permission) {
            $method = strtolower($permission->method);
            $allowedMethods = ['get', 'post', 'put', 'patch', 'delete'];

            if (! in_array($method, $allowedMethods, true)) {
                continue;
            }

            $controllerClass = "\\App\\Http\\Controllers\\{$permission->controller}";

            $middlewares = ['web', 'auth', "can:{$permission->name}"];

            $cleanUri = trim($permission->uri, '/');

            if ($cleanUri !== 'biodata' && $cleanUri !== 'teachers') {
                $middlewares[] = 'auth.biodata';
            }

            Route::group([
                'middleware' => $middlewares,
            ], function () use ($method, $permission, $controllerClass) {
                Route::match([$method], $permission->uri, [$controllerClass, $permission->action])
                    ->name("{$permission->controller}.{$permission->action}");
            });
        }
    }
}
