<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLugarToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
          $table->string('lugar');
          $table->unsignedInteger('condicion_id');
          $table->dropColumn('condiciones');
          $table->renameColumn('precios', 'moneda');
          $table->text('notas')->nullable();

          $table->foreign('condicion_id')->references('id')->on('condiciones_cotizacion')
          ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            //
        });
    }
}
