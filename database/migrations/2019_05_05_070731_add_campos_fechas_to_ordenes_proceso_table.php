<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposFechasToOrdenesProcesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_proceso', function (Blueprint $table) {
          $table->date('fecha_estimada_fabricacion')->nullable();
          $table->date('fecha_real_fabricacion')->nullable();
          $table->date('fecha_estimada_embarque')->nullable();
          $table->date('fecha_real_embarque')->nullable();
          $table->date('fecha_estimada_aduana')->nullable();
          $table->date('fecha_real_aduana')->nullable();
          $table->date('fecha_estimada_importacion')->nullable();
          $table->date('fecha_real_importacion')->nullable();
          $table->date('fecha_estimada_liberado_aduana')->nullable();
          $table->date('fecha_real_liberado_aduana')->nullable();
          $table->date('fecha_estimada_embarque_final')->nullable();
          $table->date('fecha_real_embarque_final')->nullable();
          $table->date('fecha_estimada_descarga')->nullable();
          $table->date('fecha_real_descarga')->nullable();
          $table->date('fecha_estimada_entrega')->nullable();
          $table->date('fecha_real_entrega')->nullable();
          $table->date('fecha_estimada_instalacion')->nullable();
          $table->date('fecha_real_instalacion')->nullable();
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
