<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('tipo_id');
          $table->string('nombre');
          $table->string('telefono');
          $table->string('email');
          $table->string('rfc');
          $table->string('calle')->nullable();
          $table->string('numero')->nullable();
          $table->string('colonia')->nullable();
          $table->string('cp')->nullable();
          $table->string('ciudad')->nullable();
          $table->string('estado')->nullable();

          $table->foreign('tipo_id')->references('id')->on('tipos_clientes')
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
        Schema::dropIfExists('clientes');
    }
}
