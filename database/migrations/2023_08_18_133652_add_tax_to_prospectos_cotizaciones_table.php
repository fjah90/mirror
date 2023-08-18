<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            $table->after('iva', function ($table) {
                $table->decimal('tax', 8, 2)->default(0.00);
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
            $table->dropColumn('tax');
        });
    }
}
