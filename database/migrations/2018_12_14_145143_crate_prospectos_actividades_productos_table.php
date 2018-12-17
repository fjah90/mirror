<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateProspectosActividadesProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos_actividades_productos', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('actividad_id');
          $table->unsignedInteger('producto_id');

          $table->foreign('actividad_id')->references('id')->on('prospectos_actividades')
          ->onDelete('cascade');
          $table->foreign('producto_id')->references('id')->on('productos')
          ->onDelete('cascade');

          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospectos_actividades_productos');
    }
}
