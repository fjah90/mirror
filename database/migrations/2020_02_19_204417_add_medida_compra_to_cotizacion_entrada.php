<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedidaCompraToCotizacionEntrada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
            $table->text('medida_compra')->nullable();
            $table->text('soporte_precio_compra')->nullable();
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
            $table->dropColumn('medida_compra');
            $table->dropColumn('soporte_precio_compra');
        });
    }
}
