<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAlterTableProspectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('prospectos', function (Blueprint $table) {
            $table->string('estatus')->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('prospectos', function (Blueprint $table) {
            $table->dropColumn('estatus');
       });
    }
}
