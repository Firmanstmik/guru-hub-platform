<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

$password = Hash::make('password123');

$accounts = [
    'admin@gmail.com' => 'admin',
    'guru@gmail.com' => 'guru',
    'siswa@gmail.com' => 'siswa',
];

foreach ($accounts as $email => $roleName) {
    $user = User::firstOrCreate(
        ['email' => $email],
        ['name' => ucfirst($roleName) . ' GuruHub', 'is_active' => true]
    );
    $user->password = $password;
    $user->is_active = true;
    $user->save();

    Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    if (! $user->hasRole($roleName)) {
        $user->syncRoles([$roleName]);
    }

    echo "OK: {$email} ({$roleName})\n";
}

echo "Password reset to: password123\n";
