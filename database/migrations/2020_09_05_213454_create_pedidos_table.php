<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->string("descripcion",255);
            $table->decimal('total', 9, 2);
            $table->bigInteger("mesa");
            $table->foreignId("id_restaurante")->constrained('restaurantes', 'id_restaurante');
            $table->foreignId("id_usuario")->constrained('usuarios', 'id_usuario');
            $table->foreignId("id_estado")->constrained('estados', 'id_estado');
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
        Schema::dropIfExists('pedidos');
    }
}
