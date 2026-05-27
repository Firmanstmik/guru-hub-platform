<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

class AksesController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('all-akses.index', compact('users', 'roles', 'permissions'));
    }
}

// $roles_permission = Role::with('permissions')->get();
// $permissions = Permission::pluck('name')->toArray();

// foreach ($permissionMap as $permName => $routeInfo) {
//     if (in_array($permName, $permissions)) {
//         Route::middleware(['web', 'auth', "can:$permName"])
//             ->{$routeInfo['method']}($routeInfo['uri'], [PermissionController::class, $routeInfo['action']])
//             ->name("permissions.$routeInfo[action]");
//     }
// }

// php artisan make:migration add_route_data_to_permissions_table
// public function up()
// {
//     Schema::table('permissions', function (Blueprint $table) {
//         $table->string('uri')->nullable();
//         $table->string('method')->nullable();
//         $table->string('action')->nullable();
//     });
// }

// php artisan migrate
