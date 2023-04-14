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

          $table->string('telefono')->nullable()->after('cargo');
          $table->string('email')->nullable()->after('telefono');
          $table->string('fax')->nullable()->after('email');
        
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
