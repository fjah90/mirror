<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivosFieldsToOrdenesProcesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_proceso', function (Blueprint $table) {
          $table->string('status')->default('En fabricación');
          $table->string('factura')->nullable();
          $table->string('packing')->nullable();
          $table->string('bl')->nullable();
          $table->string('certificado')->nullable();
          $table->string('gastos')->nullable();
          $table->string('pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_proceso', function (Blueprint $table) {
            //
        });
    }
}
