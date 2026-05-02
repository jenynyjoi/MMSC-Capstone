<?php

namespace App\Providers;

use App\Models\SchoolYear;
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
        // Share the active school year name with every view in the app.
        View::composer('*', function ($view) {
            try {
                $view->with('activeSchoolYear', SchoolYear::activeName());
            } catch (\Throwable $e) {
                $view->with('activeSchoolYear', '—');
            }
        });
    }
}
