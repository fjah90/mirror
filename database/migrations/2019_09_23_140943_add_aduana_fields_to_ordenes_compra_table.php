<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAduanaFieldsToOrdenesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->string('tiempo_entrega')->nullable();
            $table->string('numero_proyecto')->nullable();
            $table->unsignedInteger('aduana_id')->nullable();
            $table->string('aduana_compañia')->nullable();

            $table->foreign('aduana_id')->references('id')->on('agentes_aduanales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            //
        });
    }
}
