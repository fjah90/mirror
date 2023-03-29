<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableProveedoresContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores_contactos', function (Blueprint $table) {
          $table->string('fax')->nullable()->after('telefono');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('proveedores_contactos',function (Blueprint $table) {
         $table->dropColumn('fax');
       });
    }
}