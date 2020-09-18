<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->date('fecha_compra')->nullable();
        });

        Schema::table('ordenes_compra_entradas', function (Blueprint $table) {
            $table->text('comentarios')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compra_entradas', function (Blueprint $table) {
            $table->dropColumn('comentarios');
        });

        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropColumn('fecha_compra');
        });
    }
}
