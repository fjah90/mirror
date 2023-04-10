<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendedorIdToProspectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos', function (Blueprint $table) {
            $table->unsignedInteger('vendedor_id')->nullable();

            $table->foreign('vendedor_id')->references('id')->on('vendedores');
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
            $table->dropColumn('vendedor_id');
       });
    }
}
