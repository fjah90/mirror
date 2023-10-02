<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIconoVisibleNulleableFalse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias_descripciones', function (Blueprint $table) {
            $table->boolean('icono_visible')->default(true)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias_descripciones', function (Blueprint $table) {
            $table->dropColumn('icono_visible')->default(true)->nullable(true)->change();
        });
    }
}
