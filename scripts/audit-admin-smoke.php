#!/usr/bin/env php
<?php
/**
 * Smoke-test admin menu URLs as logged-in admin.
 * Forces permission routes to register even in CLI.
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Providers\PermissionRouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReflectionClass;

// Register dynamic permission routes (skipped by provider in console)
$provider = new PermissionRouteServiceProvider($app);
$ref = new ReflectionClass($provider);
$map = $ref->getMethod('mapDynamicPermissionRoutes');
$map->setAccessible(true);
$map->invoke($provider);

$user = User::where('email', 'admin@gmail.com')->first();
if (!$user) {
    fwrite(STDERR, "Admin user not found\n");
    exit(1);
}
Auth::login($user);

$paths = [
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
        // Follow one redirect if auth.biodata or similar
        if ($status >= 300 && $status < 400) {
            $loc = $response->headers->get('Location');
            echo "REDIR {$status}  {$path} -> {$loc}\n";
            $fail++;
        } elseif ($status >= 200 && $status < 400) {
            $ok++;
            echo "OK  {$status}  {$path}\n";
        } else {
            $fail++;
            echo "FAIL {$status}  {$path}\n";
            if ($status === 500) {
                $content = $response->getContent();
                if (preg_match('/class="exception-message"[^>]*>(.*?)</s', $content, $m)
                    || preg_match('/<title>(.*?)<\/title>/s', $content, $m)) {
                    echo "     " . trim(html_entity_decode(strip_tags($m[1]))) . "\n";
                }
            }
        }
        $kernel->terminate($request, $response);
    } catch (Throwable $e) {
        $fail++;
        echo "ERR  {$path}  " . $e->getMessage() . "\n";
    }
}

echo "\nSummary: {$ok} ok, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
