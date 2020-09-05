<?php

use Illuminate\Database\Seeder;
use \App\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::query()->delete();
        Usuario::create([
            'id_tipo_usuario' => 1,
            'nombre' => 'William Alexis',
            'apellido' => 'Lazo Vásquez',
            'password' => base64_encode('123'),
            'correo' => 'williamvasquez962@gmail.com',
            'id_estado' => 1,
            'telefono' => '7772-8911',
            'direccion' => 'San Salvador'
        ]);

        $faker = \Faker\Factory::create();

        for ($i = 0; $i <= 50; $i++) {
            Usuario::create([
                'id_tipo_usuario' => rand(1, 3),
                'nombre' => $faker->name,
                'apellido' => $faker->lastName,
                'password' => base64_encode($faker->password),
                'correo' => $faker->email,
                'id_estado' => rand(1, 3),
                'telefono' => '7' . rand(10, 99) . '7-89' . rand(10, 99),
                'direccion' => $faker->address
            ]);
        }
    }
}
