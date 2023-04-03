<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterTableProspectosCotizaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
          $table->string('documentacion')->nullable();
          $table->string('factibilidad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('prospectos_cotizaciones');
          $table->dropColumn('documentacion');
          $table->dropColumn('factibilidad');
    }
}
