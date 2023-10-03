<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconoVisibleToProspectosCotizacionesEntradasDescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones_entradas_descripciones', function (Blueprint $table) {
            $table->boolean('icono_visible')->after('valor')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospectos_cotizaciones_entradas_descripciones', function (Blueprint $table) {
            $table->dropColumn('icono_visible');
        });
    }
}
