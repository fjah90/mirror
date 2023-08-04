<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldToProspectosCotizaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            $table->decimal('flete_menor', 8, 2)->nullable()->after('fletes');
            $table->decimal('costo_sobreproduccion', 8, 2)->nullable()->after('flete_menor');
            $table->decimal('descuentos', 8, 2)->nullable()->after('iva');
            $table->enum('tipo_descuento', [0, 1])->nullable()->after('descuentos');
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
            $table->dropColumn('flete_menor');
            $table->dropColumn('costo_sobreproduccion');
            $table->dropColumn('descuentos');
            $table->dropColumn('tipo_descuento');
        });
    }
}
