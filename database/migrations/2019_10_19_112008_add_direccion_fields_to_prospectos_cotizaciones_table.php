<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDireccionFieldsToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            $table->boolean('direccion')->default(0)->nullable();
            $table->string('dircalle')->nullable();
            $table->string('dirnexterior')->nullable();
            $table->string('dirninterior')->nullable();
            $table->string('dircolonia')->nullable();
            $table->string('dircp')->nullable();
            $table->string('dirciudad')->nullable();
            $table->string('direstado')->nullable();
            $table->string('lugar')->nullable()->change();
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
            //
        });
    }
}
