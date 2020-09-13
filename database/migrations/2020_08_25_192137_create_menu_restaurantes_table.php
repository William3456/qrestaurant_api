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
            $table->id('id_menu');
            $table->string('titulo', 255);
            $table->string('descripcion', 255);
            $table->decimal('precio', 6, 2);
            $table->decimal('descuento', 3, 2);
            $table->bigInteger('promocion', );
            $table->string('url_img', 255);
            $table->foreignId("id_restaurante")->constrained('restaurantes', 'id_restaurante');
            $table->foreignId("id_tipo_menu")->constrained('tipo_menu', 'id_tipo_menu');
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
        Schema::dropIfExists('menu_restaurantes');
    }
}
