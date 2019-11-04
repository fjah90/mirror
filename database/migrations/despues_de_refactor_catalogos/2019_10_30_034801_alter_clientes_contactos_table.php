<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientesContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes_contactos', function (Blueprint $table) {
            $table->dropColumn('telefono');
            $table->dropColumn('email');
            $table->dropColumn('telefono2');
            $table->dropColumn('extencion_telefono');
            $table->dropColumn('extencion_telefono2');
            $table->dropColumn('tipo_telefono');
            $table->dropColumn('tipo_telefono2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_contactos', function (Blueprint $table) {
            //
        });
    }
}
