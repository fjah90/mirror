<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosAprobadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_aprobados', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cliente_id');
          $table->unsignedInteger('cotizacion_id');
          $table->string('cliente_nombre');
          $table->string('proyecto');
          $table->text('proveedores');
          $table->string('moneda');

          $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
          $table->foreign('cotizacion_id')->references('id')->on('prospectos_cotizaciones')
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
        Schema::dropIfExists('proyectos_aprobados');
    }
}
