<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFactorPorcentualToTiposClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_clientes', function (Blueprint $table) {
            $table->decimal('factor_porcentual',12,2)->dafault('1.0');
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
            $table->dropColumn('factor_porcentual');
        });
    }
}
