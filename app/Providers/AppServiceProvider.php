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
                $user = auth()->user();
                $userRoleIds = $user->roles->pluck('id');

                // helper: cek 1 menu boleh diakses user ini atau tidak
                $canAccess = function ($m) use ($user, $userRoleIds) {
                    if (!$m->route) return false;
                    $assigned = $m->roles->pluck('id')->intersect($userRoleIds)->isNotEmpty();
                    return $assigned && $user->can("{$m->route}.read");
                };

                $menus = Menu::whereNull('parent_id')
                    ->with(['children' => function ($q) {
                        $q->with('roles')->orderBy('order');
                    }, 'roles'])
                    ->orderBy('order')
                    ->get()
                    ->map(function ($menu) use ($canAccess) {
                        // saring children di sini, sebelum dikirim ke blade
                        $menu->setRelation('children', $menu->children->filter($canAccess)->values());
                        return $menu;
                    })
                    ->filter(function ($menu) use ($canAccess) {
                        // parent tampil kalau dia sendiri bisa diakses ATAU punya minimal 1 child valid
                        return $canAccess($menu) || $menu->children->isNotEmpty();
                    })
                    ->values();

                $view->with('menus', $menus);
            } else {
                $view->with('menus', collect());
            }
        });
    }
}
