<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware 'role' agar bisa dipakai di routes
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Pengalihan dinamis untuk user yang sudah login saat mengakses halaman guest-only (seperti /login)
        $middleware->redirectUsersTo(function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user) {
                return match ($user->role) {
                    'admin_master' => route('admin.dashboard'),
                    'kepala_dinas' => route('kepala_dinas.dashboard'),
                    'admin_pasar'  => route('admin_pasar.dashboard'),
                    default        => '/',
                };
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
