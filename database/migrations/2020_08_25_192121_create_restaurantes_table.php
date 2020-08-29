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
            $table->id('id_restaurante');
            $table->string("nombre", 255);
            $table->unsignedBigInteger('num_mesas');
            $table->foreignId('id_usuario')->nullable()
                ->constrained('usuarios', 'id_usuario')
                ->cascadeOnDelete();
            //Si le pongo onDelete('set null') en lugar de cascade, se elimina el usuario y lo asociado a este queda en null

            $table->string("telefono", 9);
            $table->string("direccion", 255);
            $table->string("correo", 255)->unique();
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
        Schema::dropIfExists('restaurantes');
    }
}
