<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosFacturacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_facturacion', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('rfc')->unique();
            $table->string('razon_social')->nullable();
            $table->string('calle')->nullable();
            $table->string('nexterior')->nullable();
            $table->string('ninterior')->nullable();
            $table->string('colonia')->nullable();
            $table->string('cp')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id', 'cliente_id_foreign')->references('id')
                ->on('clientes')->onDelete('cascade');

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
        Schema::dropIfExists('datos_facturacion');
    }
}
