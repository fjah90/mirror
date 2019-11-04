<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('numero_cliente')->nullable();
            $table->dropColumn('telefono');
            $table->dropColumn('email');
            $table->dropColumn('codigo_pais');
            $table->renameColumn('numero', 'nexterior');
            $table->string('delegacion')->nullable();
            $table->string('pagina_web')->nullable();
            $table->text('adicionales')->nullable();
            $table->string('banco_colonia')->nullable();
            $table->string('banco_delegacion')->nullable();
            $table->string('banco_ciudad')->nullable();
            $table->string('banco_estado')->nullable();
            $table->string('banco_zipcode')->nullable();
            $table->string('banco_pais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
}
