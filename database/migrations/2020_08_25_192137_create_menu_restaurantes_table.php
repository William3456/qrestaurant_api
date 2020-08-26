<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuRestaurantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_restaurantes', function (Blueprint $table) {
            $table->bigIncrements('id_menu');
            $table->string('titulo', 255);
            $table->string('descripcion', 255);
            $table->decimal('precio', 6, 2);
            $table->decimal('descuento', 3, 2);
            $table->string('url_img', 255);
            $table->unsignedBigInteger("id_restaurante");
            $table->timestamps();
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_restaurantes');
    }
}
