<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        // Tangkap PostTooLargeException sebelum sampai ke controller
        // agar user tidak dilempar ke halaman error Laravel yang menakutkan
        $exceptions->renderable(function (PostTooLargeException $e, Request $request) {
            $maxMb = round(ini_get('upload_max_filesize') ?: 2, 1);
            return redirect()->back()
                ->withInput($request->except(['foto_rectifier', 'foto_battery', 'foto_kwh', 'foto_genset', 'foto_ac', 'foto', 'foto_rma']))
                ->with('error_upload', "Ukuran file yang diunggah melebihi batas maksimal ({$maxMb} MB). Silakan kompres foto terlebih dahulu lalu coba lagi.");
        });
    })->create();

