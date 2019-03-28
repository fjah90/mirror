<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasPagarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_pagar', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('proveedor_id');
          $table->unsignedInteger('orden_compra_id');
          $table->string('proveedor_empresa');
          $table->string('proyecto_nombre');
          $table->integer('dias_credito');
          $table->string('moneda');
          $table->decimal('total',12,2);
          $table->decimal('facturado',12,2)->default(0);
          $table->decimal('pagado',12,2)->default(0);
          $table->decimal('pendiente',12,2);
          $table->boolean('pagada')->default(0);

          $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
          $table->foreign('orden_compra_id')->references('id')->on('ordenes_compra')
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
        Schema::dropIfExists('cuentas_pagar');
    }
}
