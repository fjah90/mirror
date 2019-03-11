<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasCobrarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_cobrar', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cliente_id');
          $table->unsignedInteger('cotizacion_id');
          $table->string('cliente');
          $table->string('proyecto');
          $table->string('condiciones');
          $table->string('moneda');
          $table->decimal('total',12,2);
          $table->decimal('facturado',12,2)->default(0);
          $table->decimal('pagado',12,2)->default(0);
          $table->decimal('pendiente',12,2);
          $table->string('comprobante_confirmacion');
          $table->boolean('pagada')->default(0);

          $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
          $table->foreign('cotizacion_id')->references('id')->on('prospectos_cotizaciones')
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
        Schema::dropIfExists('cuentas_cobrar');
    }
}
