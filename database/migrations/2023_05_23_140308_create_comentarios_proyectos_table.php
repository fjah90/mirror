<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios_proyectos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('vendedor_id');
            $table->unsignedInteger('prospecto_id');
            $table->string('comentarios')->nullable();

            $table->foreign('cliente_id')->references('id')->on('comentarios_proyectos')
            ->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('comentarios_proyectos')
            ->onDelete('cascade');

            $table->foreign('vendedor_id')->references('id')->on('comentarios_proyectos')
            ->onDelete('cascade');

            $table->foreign('prospecto_id')->references('id')->on('comentarios_proyectos')
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
        Schema::dropIfExists('comentarios_proyectos');
    }
}
