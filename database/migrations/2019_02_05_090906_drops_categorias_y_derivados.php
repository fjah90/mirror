<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropsCategoriasYDerivados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      DB::table('prospectos_actividades_productos')->truncate();
      Schema::dropIfExists('prospectos_cotizaciones_entradas');
      DB::table('prospectos_cotizaciones')->truncate();
      Schema::dropIfExists('productos');
      Schema::dropIfExists('categorias');
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
