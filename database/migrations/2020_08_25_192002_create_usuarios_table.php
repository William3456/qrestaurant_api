<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->unsignedBigInteger("id_tipo_usuario")->default('1');;
            $table->unsignedBigInteger("restaurante_asociado")->nullable();
            $table->string("nombre",255);
            $table->string("apellido",255);
            $table->string("password",255);
            $table->string("correo",255)->unique();
            $table->unsignedBigInteger("estado");
            $table->string("telefono",9)->unique();
            $table->string("direccion",255);
            $table->string("api_token",60)->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
