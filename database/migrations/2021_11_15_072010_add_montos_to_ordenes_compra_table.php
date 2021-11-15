<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMontosToOrdenesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
          $table->decimal('monto_total_producto', 12, 2)->default(0);
          $table->decimal('monto_total_flete', 12, 2)->default(0);
          $table->decimal('posibles_aumentos', 12, 2)->default(0);
          $table->decimal('tax', 12, 2)->default(0);
          $table->decimal('monto_total_pagar', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes', function (Blueprint $table) {
             $table->dropColumn('monto_total_pagar');
             $table->dropColumn('monto_total_producto');
             $table->dropColumn('monto_total_flete');
             $table->dropColumn('posibles_aumentos');
             $table->dropColumn('tax');
        });
    }
}
