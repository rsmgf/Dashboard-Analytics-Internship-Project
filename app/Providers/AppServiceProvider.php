<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Pop;
use App\Models\Rectifier;
use App\Models\Kwh;
use App\Models\Battery;
use App\Models\Ac;
use App\Models\Genset;
use App\Observers\DashboardCacheObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        Pop::observe(DashboardCacheObserver::class);
        Rectifier::observe(DashboardCacheObserver::class);
        Kwh::observe(DashboardCacheObserver::class);
        Battery::observe(DashboardCacheObserver::class);
        Ac::observe(DashboardCacheObserver::class);
        Genset::observe(DashboardCacheObserver::class);

        // Manajer dan super_admin keduanya punya semua permission.
        // Perbedaan mode (sidebar/tidak) hanya diatur via session active_role — bukan via Gate.
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin') || $user->hasRole('manajer')) {
                return true;
            }
            return null;
        });

        View::composer('components.sidebar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $userRoleIds = $user->roles->pluck('id');

                // helper: cek 1 menu boleh diakses user ini atau tidak
                $canAccess = function ($m) use ($user, $userRoleIds) {
                    if (!$m->route) return false;
                    $assigned = $m->roles->pluck('id')->intersect($userRoleIds)->isNotEmpty();
                    return $assigned && $user->can("{$m->route}.read");
                };

                $menus = Menu::whereNull('parent_id')
                    ->where('is_sidebar', true)
                    ->with(['children' => function ($q) {
                        $q->where('is_sidebar', true)->with('roles')->orderBy('order');
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
