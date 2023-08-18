<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->renameColumn('precio', 'precio_unitario');
            $table->decimal('precio_residencial', 8, 2)->nullable()->after('precio_unitario');
            $table->decimal('precio_comercial', 8, 2)->nullable()->after('precio_residencial');
            $table->decimal('precio_distribuidor', 8, 2)->nullable()->after('precio_comercial');
            $table->string('color')->nullable()->after('nombre_material');
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
            $table->renameColumn('precio_unitario', 'precio');
            $table->dropColumn('precio_residencial');
            $table->dropColumn('precio_comercial');
            $table->dropColumn('precio_distribuidor');
            $table->dropColumn('color');
        });
    }
}
