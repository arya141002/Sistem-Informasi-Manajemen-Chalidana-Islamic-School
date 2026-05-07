<?php

namespace App\Providers;

use App\Models\Campus;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $helperPath = app_path('Helpers/GlobalHelper.php');

        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $userRole = Auth::user()->role_id;

                if (! $userRole) {
                    $view->with('sidebarMenus', collect());
                    return;
                }

                $sidebarMenus = Menu::with(['childrenRecursive' => function($q) use ($userRole) {
                        // Filter submenu berdasarkan kolom role_access
                        $q->where('role_access', 'LIKE', '%' . $userRole . '%')
                        ->orderBy('order', 'asc');
                    }])
                    ->whereNull('parent_id')
                    ->where('role_access', 'LIKE', '%' . $userRole . '%') // Filter menu utama
                    ->orderBy('order', 'asc')
                    ->get();
            } else {
                $sidebarMenus = collect();
            }

            $view->with('sidebarMenus', $sidebarMenus);
        });

        View::composer('layouts.front', function ($view) {
            $frontCampuses = Campus::query()
                ->whereNull('deleted_at')
                ->select('title', 'slug', 'school_tour')
                ->orderBy('title')
                ->get();

            $view->with('frontCampuses', $frontCampuses);
        });
    }
}
