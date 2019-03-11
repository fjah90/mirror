<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->string('empresa');
          $table->string('telefono');
          $table->string('email');
          $table->string('rfc');
          $table->string('banco')->nullable();
          $table->string('numero_cuenta')->nullable();
          $table->string('clave_interbancaria')->nullable();
          $table->string('moneda')->nullable();
          $table->int('dias_credito')->default(0);
          $table->string('calle')->nullable();
          $table->string('numero')->nullable();
          $table->string('colonia')->nullable();
          $table->string('cp')->nullable();
          $table->string('ciudad')->nullable();
          $table->string('estado')->nullable();

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
        Schema::dropIfExists('proveedores');
    }
}
