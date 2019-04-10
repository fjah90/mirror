<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;

use App\Models\ProyectoAprobado;
use App\Observers\ProyectoAprobadoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);

      Blade::directive('format_money', function ($expression) {
          return "<?php echo '$'.number_format($expression, 2); ?>";
      });

      Blade::directive('format_number', function ($expression) {
          return "<?php echo number_format($expression, 2); ?>";
      });

      Blade::directive('text_capitalize', function ($expression) {
          return "<?php echo ucfirst(mb_strtolower($expression, 'UTF-8')); ?>";
      });

      Route::resourceVerbs([
        'create' => 'crear',
        'edit' => 'editar',
      ]);

      //Observadores
      ProyectoAprobado::observe(ProyectoAprobadoObserver::class);
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
