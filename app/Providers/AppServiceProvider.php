<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Blade::directive('format_money', function ($expression) {
          return "<?php echo '$'.number_format($expression, 2); ?>";
      });

      Blade::directive('format_number', function ($expression) {
          return "<?php echo number_format($expression, 2); ?>";
      });

      Route::resourceVerbs([
        'create' => 'crear',
        'edit' => 'editar',
      ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
