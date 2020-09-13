<?php

use Illuminate\Database\Seeder;
use \App\TipoUsuario;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoUsuario::create([
            'Descripcion' => 'Administrador',
        ]);
        TipoUsuario::create([
            'Descripcion' => 'Cliente',
        ]);

        TipoUsuario::create([
            'Descripcion' => 'Empleado',
        ]);

        TipoUsuario::create([
            'Descripcion' => 'Encargado restaurante',
        ]);
    }
}
