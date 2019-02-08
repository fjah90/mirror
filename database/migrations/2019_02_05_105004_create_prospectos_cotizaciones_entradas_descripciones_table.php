<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectosCotizacionesEntradasDescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos_cotizaciones_entradas_descripciones', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('entrada_id');
          $table->string('nombre')->nullable();
          $table->string('name')->nullable();
          $table->string('valor');

          $table->foreign('entrada_id', 'entrada_foreign')->references('id')
          ->on('prospectos_cotizaciones_entradas')->onDelete('cascade');

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
        Schema::dropIfExists('prospectos_cotizaciones_entradas_descripciones');
    }
}
