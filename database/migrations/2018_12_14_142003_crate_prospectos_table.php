<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateProspectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectos', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cliente_id');
          $table->text('descripcion');

          $table->foreign('cliente_id')->references('id')->on('clientes')
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
        Schema::dropIfExists('prospectos');
    }
}
