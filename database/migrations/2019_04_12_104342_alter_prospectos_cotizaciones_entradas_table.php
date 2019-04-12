<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProspectosCotizacionesEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
          $table->renameColumn('foto', 'fotos');
          $table->renameColumn('observacion', 'observaciones');
        });
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
          $table->text('fotos')->nullable()->change();
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
