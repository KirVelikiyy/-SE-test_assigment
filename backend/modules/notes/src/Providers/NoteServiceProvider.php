<?php

namespace Notes\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        $this->configureFactoriesPath();
    }

    private function registerRoutes(): void
    {
      Route::prefix('api')
         ->group(__DIR__ . '/../../routes/api.php');
    }

    private function configureFactoriesPath(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }
}
