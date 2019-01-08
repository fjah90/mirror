<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos_cotizaciones', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('prospecto_id');
          $table->date('fecha');
          $table->decimal('subtotal', 12, 2);
          $table->decimal('iva', 12, 2);
          $table->decimal('total', 12, 2);
          $table->text('observaciones')->nullable();
          $table->string('archivo')->nullable();

          $table->foreign('prospecto_id')->references('id')->on('prospectos')
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
        Schema::dropIfExists('prospectos_cotizaciones');
    }
}
