#!/usr/bin/env php
<?php
/**
 * Smoke-test key pages as logged-in admin.
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'admin@gmail.com')->first();
if (! $user) {
    fwrite(STDERR, "Admin user not found\n");
    exit(1);
}
Auth::login($user);

$paths = [
    '/',
    '/login',
    '/admin-dashboard',
    '/company-accounts',
    '/categories',
    '/courses',
    '/users',
    '/teachers',
    '/student-biodata',
    '/materials',
    '/videos',
    '/course-students',
    '/schedules',
    '/bookings',
    '/certificates',
    '/homepage-testimonials',
    '/reviews',
    '/payments',
    '/earnings',
    '/akses',
    '/roles',
    '/permissions',
    '/users-manage',
];

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$fail = 0;
$ok = 0;

foreach ($paths as $path) {
    $request = Request::create($path, 'GET');
    $request->setLaravelSession($app['session.store']);
    $request->setUserResolver(fn () => Auth::user());
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status >= 200 && $status < 400) {
            $ok++;
            echo "OK  {$status}  {$path}\n";
        } else {
            $fail++;
            echo "FAIL {$status}  {$path}\n";
        }
        $kernel->terminate($request, $response);
    } catch (Throwable $e) {
        $fail++;
        echo "ERR  {$path}  " . $e->getMessage() . "\n";
    }
}

echo "\nSummary: {$ok} ok, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
