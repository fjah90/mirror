<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVistaUnidadesMedidaConversiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement("
        CREATE VIEW vista_unidades_medida_conversiones AS
        SELECT unidades_medida_conversiones.*,
                unidades_medida.simbolo AS unidad_conversion_simbolo,
                unidades_medida.nombre AS unidad_conversion_nombre
        FROM unidades_medida_conversiones JOIN unidades_medida
        ON unidades_medida_conversiones.unidad_conversion_id = unidades_medida.id
      ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::statement('DROP VIEW IF EXISTS vista_unidades_medida_conversiones');
    }
}
