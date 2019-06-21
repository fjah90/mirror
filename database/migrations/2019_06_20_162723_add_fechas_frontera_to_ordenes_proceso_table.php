<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFechasFronteraToOrdenesProcesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_proceso', function (Blueprint $table) {
          $table->date('fecha_estimada_frontera')->nullable();
          $table->date('fecha_real_frontera')->nullable();
          $table->string('deposito_warehouse')->nullable();
          $table->string('carta_entrega')->nullable();
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
