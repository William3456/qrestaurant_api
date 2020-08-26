<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->bigIncrements('id_restaurante');
            $table->string("nombre",255);
            $table->unsignedBigInteger('num_mesas');
            $table->unsignedBigInteger('id_usuario');
            $table->string("telefono",9);
            $table->string("direccion",255);
            $table->string("correo",255)->unique();
            $table->timestamps();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurantes');
    }
}
