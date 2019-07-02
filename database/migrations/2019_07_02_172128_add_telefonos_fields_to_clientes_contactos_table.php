<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTelefonosFieldsToClientesContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes_contactos', function (Blueprint $table) {
          $table->string('telefono2')->nullable();
          $table->string('extencion_telefono')->nullable();
          $table->string('extencion_telefono2')->nullable();
          $table->string('tipo_telefono')->nullable();
          $table->string('tipo_telefono2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_contactos', function (Blueprint $table) {
            //
        });
    }
}
