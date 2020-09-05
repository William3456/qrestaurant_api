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
            $table->id('id_usuario');
            $table->foreignId("id_tipo_usuario")->constrained('tipo_usuarios', 'id_tipo_usuario');
            $table->unsignedBigInteger("restaurante_asociado")->nullable();
            $table->foreignId("id_estado")->constrained('estados', 'id_estado');
            $table->string("nombre",255);
            $table->string("apellido",255);
            $table->string("password",255);
            $table->string("correo",255)->unique();
            $table->string("telefono",9)->unique();
            $table->string("direccion",255);
            $table->rememberToken();
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
        Schema::dropIfExists('usuarios');
    }
}
