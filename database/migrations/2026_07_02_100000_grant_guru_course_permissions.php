<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guru = Role::findByName('guru', 'web');

        foreach (['courses-add', 'courses-edit', 'courses-hapus'] as $name) {
            $permission = Permission::where('name', $name)->where('guard_name', 'web')->first();

            if ($permission && ! $guru->hasPermissionTo($permission)) {
                $guru->givePermissionTo($permission);
            }
        }
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guru = Role::findByName('guru', 'web');

        foreach (['courses-add', 'courses-edit', 'courses-hapus'] as $name) {
            $permission = Permission::where('name', $name)->where('guard_name', 'web')->first();

            if ($permission && $guru->hasPermissionTo($permission)) {
                $guru->revokePermissionTo($permission);
            }
        }
    }
};
