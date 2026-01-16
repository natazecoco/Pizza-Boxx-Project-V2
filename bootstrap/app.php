<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', // Pastikan rute web dimuat
        api: __DIR__.'/../routes/api.php', // Pastikan rute API dimuat
        commands: __DIR__.'/../routes/console.php', // Pastikan rute konsol dimuat
        health: '/up', // Endpoint kesehatan aplikasi
    )
    // ->withMiddleware(function (Middleware $middleware) {
    //     $middleware->alias([
    //         'role' => RoleMiddleware::class,
    //         'permission' => PermissionMiddleware::class,
    //     ]);
    // })

    // Ganti middleware Role dengan middleware kustom buatan
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Ganti RoleMiddleware milik Spatie dengan file buatan sendiri
            'role' => \App\Http\Middleware\CheckRole::class, 
            
            // Tetap biarkan permission jika kamu membutuhkannya nanti
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            // Jika user mencoba akses yang dilarang, balikkan ke dashboard dengan pesan
            return redirect()->route('pegawai.dashboard')
                ->with('error', 'Maaf, area tersebut hanya untuk Admin Pusat.');
        });
    })

    ->create();
