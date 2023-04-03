<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('clientes', function (Blueprint $table) {
          $table->unsignedInteger('categoria_cliente_id')->nullable();
          $table->foreign('categoria_cliente_id')->references('id')->on('categoria_cliente');
          $table->string('preferencias')->nullable();
      });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('clientes',function (Blueprint $table) {
         $table->dropColumn('categoria_cliente_id');
         $table->dropColumn('preferencias');
       });
    }
}
