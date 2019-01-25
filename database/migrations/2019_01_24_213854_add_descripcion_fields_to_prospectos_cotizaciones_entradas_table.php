<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescripcionFieldsToProspectosCotizacionesEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
          $table->string('descripcion1')->nullable();
          $table->string('descripcion2')->nullable();
          $table->string('descripcion3')->nullable();
          $table->string('descripcion4')->nullable();
          $table->string('descripcion5')->nullable();
          $table->string('descripcion6')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
            //
        });
    }
}
