<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@gmail.com')->first();

if (!$user) {
    echo "USER_NOT_FOUND\n";
    exit(1);
}

$passOk = Hash::check('password123', $user->password);
$role = $user->getRoleNames()->first() ?? 'none';
$active = $user->is_active ?? 'null';

echo "email={$user->email}\n";
echo "password_ok=" . ($passOk ? 'yes' : 'no') . "\n";
echo "is_active={$active}\n";
echo "role={$role}\n";
