<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.sidebar', function ($view) {
        if (auth()->check()) {
            $roleIds = auth()->user()->roles->pluck('id');

            $menus = Menu::whereNull('parent_id')
                ->whereHas('roles', fn($q) => $q->whereIn('roles.id', $roleIds))
                ->with(['children' => function ($q) use ($roleIds) {
                    $q->whereHas('roles', fn($q2) => $q2->whereIn('roles.id', $roleIds));
                }])
                ->orderBy('order')
                ->get();

            $view->with('menus', $menus);
        }
    });
    }
}
