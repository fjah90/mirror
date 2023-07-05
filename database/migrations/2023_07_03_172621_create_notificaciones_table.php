<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_creo')->nullable();
            $table->foreign('user_creo')->references('id')->on('users');$table->unsignedInteger('user_dirigido')->nullable();
            $table->foreign('user_dirigido')->references('id')->on('users');
            $table->text('texto');
            $table->string('status')->default('sin leer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
}
