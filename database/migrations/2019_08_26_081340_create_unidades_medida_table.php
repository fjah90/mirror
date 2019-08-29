<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesMedidaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_medida', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->string('simbolo');
          $table->string('nombre')->nullable();

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
        Schema::dropIfExists('unidades_medida');
    }
}
