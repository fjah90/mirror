<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactoToCotizaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            $table->string('contacto_email')->nullable();
            $table->string('contacto_telefono')->nullable();
            $table->string('contacto_nombre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospectos_cotizaciones', function (Blueprint $table) {
            $table->dropColumn('contacto_email');
            $table->dropColumn('contacto_telefono');
            $table->dropColumn('contacto_nombre');
        });
    }
}
