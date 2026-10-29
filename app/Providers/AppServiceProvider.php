<?php

namespace App\Providers;

use App\Models\Campus;
use App\Models\SocialMedia;
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

                $roleKey = (string) $userRole;

                $sidebarMenus = Menu::with(['childrenRecursive' => function($q) use ($roleKey) {
                        // Filter submenu berdasarkan kolom role_access
                        $q->where(function ($query) use ($roleKey) {
                            $query->whereJsonContains('role_access', $roleKey)
                                  ->orWhereJsonContains('role_access', intval($roleKey));
                        })->orderBy('order', 'asc');
                    }])
                    ->whereNull('parent_id')
                    ->where(function ($query) use ($roleKey) {
                        $query->whereJsonContains('role_access', $roleKey)
                              ->orWhereJsonContains('role_access', intval($roleKey));
                    }) // Filter menu utama
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

            $socialMedias = SocialMedia::query()
                ->select('title', 'link')
                ->orderBy('id')
                ->get();

            $view->with([
                'frontCampuses' => $frontCampuses,
                'socialMedias' => $socialMedias,
            ]);
        });
    }
}
