<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateProspectosActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos_actividades', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('prospecto_id');
          $table->unsignedInteger('tipo_id');
          $table->date('fecha');
          $table->text('descripcion')->nullable();
          $table->boolean('realizada')->default(0);

          $table->foreign('prospecto_id')->references('id')->on('prospectos')
          ->onDelete('cascade');
          $table->foreign('tipo_id')->references('id')->on('prospectos_tipos_actividades')
          ->onDelete('cascade');

          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospectos_actividades');
    }
}
