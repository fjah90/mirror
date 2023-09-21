<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconoVisibleToProductosDescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_descripciones', function (Blueprint $table) {
            $table->boolean('icono_visible')->after('valor')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos_descripciones', function (Blueprint $table) {
            $table->dropColumn('icono_visible');
        });
    }
}
