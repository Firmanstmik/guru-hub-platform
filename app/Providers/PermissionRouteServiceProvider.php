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

            Route::group([
                'middleware' => ['web', 'auth', "can:{$permission->name}"],
            ], function () use ($method, $permission, $controllerClass) {
                Route::match([$method], $permission->uri, [$controllerClass, $permission->action])
                    ->name("{$permission->controller}.{$permission->action}");
            });
        }
    }
}
