<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioCompraToProspectosCotizacionesEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones_entradas', function (Blueprint $table) {
            $table->decimal('precio_compra', 12, 2)->nullable();
            $table->date('fecha_precio_compra')->nullable();
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
            $table->dropColumn('precio_compra');
            $table->dropColumn('fecha_precio_compra');
        });
    }
}
