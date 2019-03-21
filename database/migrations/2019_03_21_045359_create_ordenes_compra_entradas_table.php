<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesCompraEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compra_entradas', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('orden_id');
          $table->unsignedInteger('producto_id');
          $table->decimal('cantidad', 12, 2);
          $table->string('medida');
          $table->string('conversion')->nullable();
          $table->decimal('cantidad_convertida', 12, 2)->nullable();
          $table->decimal('precio', 12, 2);
          $table->decimal('importe', 12, 2);

          $table->foreign('orden_id')->references('id')->on('ordenes_compra')
            ->onDelete('cascade');
          $table->foreign('producto_id')->references('id')->on('productos')
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
        Schema::dropIfExists('ordenes_compra_entradas');
    }
}
