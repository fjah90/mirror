<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValorInglesToOrdenesCompraEntradasDescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compra_entradas_descripciones', function (Blueprint $table) {
            $table->text('valor_ingles')->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compra_entradas_descripciones', function (Blueprint $table) {
            $table->dropColumn('valor_ingles');
        });
    }
}
