<?php

use Illuminate\Database\Seeder;
use \App\Estados;
class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estados::create([
            'descripcion' => 'Activo',
        ]);
        Estados::create([
            'descripcion' => 'Inactivo',
        ]);
        Estados::create([
            'descripcion' => 'En proceso',
        ]);
        Estados::create([
            'descripcion' => 'Finalizado',
        ]);
    }
}
