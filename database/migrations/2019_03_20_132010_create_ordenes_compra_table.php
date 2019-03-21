<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cliente_id');
          $table->unsignedInteger('proyecto_id');
          $table->unsignedInteger('proveedor_id');
          $table->unsignedInteger('orden_proceso_id')->nullable();
          $table->string('cliente_nombre');
          $table->string('proyecto_nombre');
          $table->string('proveedor_empresa');
          $table->string('status')->default('Pendiente');
          $table->string('orden_proceso_status')->nullable();
          $table->string('moneda');
          $table->decimal('subtotal', 12, 2)->default(0);
          $table->decimal('iva', 12, 2)->default(0);
          $table->decimal('total', 12, 2)->default(0);

          $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
          $table->foreign('proyecto_id')->references('id')->on('proyectos_aprobados')
            ->onDelete('cascade');
          $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');

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
        Schema::dropIfExists('ordenes_compra');
    }
}
