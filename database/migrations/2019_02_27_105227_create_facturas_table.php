<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('cuenta_id');
          $table->string('documento');
          $table->decimal('monto', 12, 2);
          $table->date('vencimiento');
          $table->decimal('pendiente', 12, 2);
          $table->decimal('pagado', 12, 2)->default(0);
          $table->boolean('pagada')->default(0);
          $table->string('pdf');
          $table->string('xml');

          $table->foreign('cuenta_id')->references('id')->on('cuentas_cobrar')
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
        Schema::dropIfExists('facturas');
    }
}
