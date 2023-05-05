<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableDatosFacturacionNewRelaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_facturacion', function (Blueprint $table) {
        $table->unsignedInteger('cat_regimen_id')->index()->nullable();
        $table->foreign('cat_regimen_id')->references('id')->on('cat_regimen');
        $table->unsignedInteger('cat_forma_pago_id')->index()->nullable();
        $table->foreign('cat_forma_pago_id')->references('id')->on('cat_forma_pago');
        $table->unsignedInteger('cat_cfdi_id')->index()->nullable();
        $table->foreign('cat_cfdi_id')->references('id')->on('cat_cfdi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_facturacion', function (Blueprint $table) {
            $table->dropColumn('cat_regimen_id');
            $table->dropColumn('cat_forma_pago_id');
            $table->dropColumn('cat_cfdi_id');   
        });
    }
}
