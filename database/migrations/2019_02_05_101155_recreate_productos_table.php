<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('proveedor_id');
          $table->unsignedInteger('categoria_id');
          $table->string('nombre');
          $table->string('name')->nullable();
          $table->string('foto')->nullable();

          $table->foreign('proveedor_id')->references('id')->on('proveedores')
          ->onDelete('cascade');
          $table->foreign('categoria_id')->references('id')->on('categorias')
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
        Schema::dropIfExists('productos');
    }
}
