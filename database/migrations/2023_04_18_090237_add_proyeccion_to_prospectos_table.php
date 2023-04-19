<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProyeccionToProspectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos', function (Blueprint $table) {
            $table->decimal('proyeccion_venta',12,2)->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->string('factibilidad')->nullable();
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
            $table->dropColumn('proyeccion_venta');
            $table->dropColumn('fecha_cierre');
            $table->dropColumn('factibilidad');
       });
    }
}
