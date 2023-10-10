<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFactorPorcentualToTiposClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_clientes', function (Blueprint $table) {
            $table->dropColumn('factor_porcentual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_clientes', function (Blueprint $table) {
            $table->integer('factor_porcentual')->after('nombre');
        });
    }
}
