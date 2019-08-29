<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesMedidaConversionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_medida_conversiones', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->unsignedInteger('unidad_medida_id');
          $table->unsignedInteger('unidad_conversion_id');
          $table->string('unidad_conversion_simbolo');
          $table->string('unidad_conversion_nombre')->nullable();
          $table->decimal('factor_conversion',7,4);

          $table->foreign('unidad_medida_id')->references('id')->on('unidades_medida');
          $table->foreign('unidad_conversion_id')->references('id')->on('unidades_medida');

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
        Schema::dropIfExists('unidades_medida_conversiones');
    }
}
