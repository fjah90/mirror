<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableClientesContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('clientes_contactos', function (Blueprint $table) {
          $table->string('fax')->default(0)->after('telefono');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('clientes_contactos',function (Blueprint $table) {
         $table->dropColumn('fax');
       });
    }
}
