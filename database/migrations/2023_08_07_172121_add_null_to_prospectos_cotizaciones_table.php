<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullToProspectosCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `prospectos_cotizaciones` 
        DROP FOREIGN KEY `prospectos_cotizaciones_prospecto_id_foreign`;
        ALTER TABLE `prospectos_cotizaciones` 
        CHANGE COLUMN `prospecto_id` `prospecto_id` INT UNSIGNED NULL ;
        ALTER TABLE `prospectos_cotizaciones` 
        ADD CONSTRAINT `prospectos_cotizaciones_prospecto_id_foreign`
          FOREIGN KEY (`prospecto_id`)
          REFERENCES `prospectos` (`id`)
          ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
