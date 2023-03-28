<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableProveedoresNewCamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('proveedores', function (Blueprint $table) {
          $table->string('decripcion_empresa')->nullable()->after('moneda');
          $table->string('productos')->nullable()->after('decripcion_empresa');
          $table->string('precios')->nullable()->after('productos');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('proveedores',function (Blueprint $table) {
         $table->dropColumn('decripcion_empresa');
         $table->dropColumn('productos');
         $table->dropColumn('precios');
       });
    }
}
