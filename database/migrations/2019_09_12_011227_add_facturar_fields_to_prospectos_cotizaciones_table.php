<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacturarFieldsToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
          $table->renameColumn('facturar', 'razon_social');
        });
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
          $table->string('rfc')->nullable();
          $table->string('calle')->nullable();
          $table->string('nexterior')->nullable();
          $table->string('ninterior')->nullable();
          $table->string('colonia')->nullable();
          $table->string('cp')->nullable();
          $table->string('ciudad')->nullable();
          $table->string('estado')->nullable();
          $table->boolean('facturar')->default(0)->nullable();
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
