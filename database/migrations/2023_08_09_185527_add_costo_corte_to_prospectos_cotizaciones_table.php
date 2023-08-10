<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostoCorteToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
                $table->decimal('costo_corte', 8, 2)->nullable()->before('costo_sobreproduccion');
            });
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
            Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
                $table->dropColumn('costo_corte');
            });
        });
    }
}