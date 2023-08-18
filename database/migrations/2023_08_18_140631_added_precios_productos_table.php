<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedPreciosProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_residencial', 8, 2)->nullable()->after('precio_unitario');
            $table->decimal('precio_comercial', 8, 2)->nullable()->after('precio_residencial');
            $table->decimal('precio_distribuidor', 8, 2)->nullable()->after('precio_comercial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('precio_residencial');
            $table->dropColumn('precio_comercial');
            $table->dropColumn('precio_distribuidor');
        });
    }
}