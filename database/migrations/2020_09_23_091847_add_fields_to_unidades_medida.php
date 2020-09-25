<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUnidadesMedida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidades_medida', function (Blueprint $table) {
            $table->string('simbolo_ingles')->nullable();
            $table->string('nombre_ingles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades_medida', function (Blueprint $table) {
            $table->dropColumn(['simbolo_ingles', 'nombre_ingles'])->nullable();
        });
    }
}
