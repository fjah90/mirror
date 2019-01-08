<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectosCotizacionesEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos_cotizaciones_entradas', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cotizacion_id');
          $table->unsignedInteger('producto_id');
          $table->string('coleccion')->nullable();
          $table->string('diseno')->nullable();
          $table->string('color')->nullable();
          $table->decimal('cantidad', 12, 2);
          $table->decimal('precio', 12, 2);
          $table->decimal('importe', 12, 2);
          $table->text('observacion')->nullable();

          $table->foreign('cotizacion_id')->references('id')->on('prospectos_cotizaciones')
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
        Schema::dropIfExists('prospectos_cotizaciones_entradas');
    }
}
