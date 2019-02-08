<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosDescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_descripciones', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('producto_id');
          $table->unsignedInteger('categoria_descripcion_id');
          $table->string('valor')->nullable();

          $table->foreign('producto_id')->references('id')->on('productos')
          ->onDelete('cascade');
          $table->foreign('categoria_descripcion_id', 'categoria_descripcion_foreign')
          ->references('id')->on('categorias_descripciones')->onDelete('cascade');

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
        Schema::dropIfExists('productos_descripciones');
    }
}
