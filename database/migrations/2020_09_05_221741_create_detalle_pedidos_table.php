<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id('id_detalle_pedido');
            $table->bigInteger('cantidad');
            $table->decimal('sub_total',9,2);
            $table->foreignId("id_pedido")->constrained('pedidos', 'id_pedido');
            $table->foreignId("id_menu_restaurante")->constrained('menu_restaurantes', 'id_menu');
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
        Schema::dropIfExists('detalle_pedidos');
    }
}
