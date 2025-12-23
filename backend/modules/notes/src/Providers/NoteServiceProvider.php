<?php

namespace Notes\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class NoteServiceProvider extends ServiceProvider {
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
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
      Route::prefix('api')
         ->group(__DIR__ . '/../../routes/api.php');
    }
}
