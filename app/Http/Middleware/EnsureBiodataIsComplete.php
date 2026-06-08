<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBiodataIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->hasRole('siswa')) {
            if (!$user->studentBiodata) {
                // Pastikan tidak kena redirect loop saat mengakses rute biodata siswa
                if (!$request->is('biodata*')) {
                    return redirect('biodata')
                        ->with('error', 'Anda harus melengkapi biodata siswa terlebih dahulu.');
                }
            }
        }

        if ($user->hasRole('guru')) {
            // Asumsi Anda akan/sudah membuat relasi 'teacherProfile' di model User
            if (!$user->teacherProfile) {
                // Pastikan tidak kena redirect loop saat mengakses rute biodata guru
                if (!$request->is('teachers*')) {
                    return redirect('teachers')
                        ->with('error', 'Anda harus melengkapi biodata pengajar terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}