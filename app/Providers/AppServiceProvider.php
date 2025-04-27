<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;  // Ensure this line is present

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('sidebar-link', \App\View\Components\SidebarLink::class);
    }

    public function register()
    {
        //
    }
    protected function mapSuperAdminRoutes()
{
    Route::prefix('superadmin')
         ->middleware(['web', 'auth', 'role:superadmin'])
         ->namespace($this->namespace.'\SuperAdmin')
         ->group(base_path('routes/superadmin.php'));
}
}

