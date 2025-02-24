<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertar datos en la tabla modulos
        DB::table('modulos')->insert([
            [
                'id' => 1,
                'nombre' => 'BK81TRFwhGEdTarfGHuNmBRmQTXBzm4mqYmjMCjihMQ=',
                'descripcion' => 'MODULO PRINCIPAL DE ACCESO A USUARIOS',
                'created_at' => Carbon::create('2024', '09', '20', '22', '18', '02'),
                'updated_at' => Carbon::create('2024', '09', '20', '22', '18', '02'),
            ],
            [
                'id' => 2,
                'nombre' => 'ykWt58rZ6Siy2XXy8UGnBmgxRdA9ChHrlhSXmsg70tc=',
                'descripcion' => 'MODULO PRINCIPAL DE ACCESO A EMPRESAS',
                'created_at' => Carbon::create('2024', '09', '20', '22', '38', '51'),
                'updated_at' => Carbon::create('2024', '09', '20', '22', '38', '51'),
            ],
            [
                'id' => 4,
                'nombre' => 'L9xia1wZk4C/O8QnurfZrd89Mvu7baCkzjMm9PUBXiU=',
                'descripcion' => 'MODULO PRINCIPAL DE ACCESO A INCAPACIDAD',
                'created_at' => Carbon::create('2024', '09', '24', '22', '44', '32'),
                'updated_at' => Carbon::create('2024', '09', '24', '22', '44', '32'),
            ],
        ]);

        // Insertar datos en la tabla permisos
        DB::table('permisos')->insert([
            [
                'id' => 1,
                'nombre' => '7LH2dmhuebtRk8lknwpNSmzvLNDeH/5/IihgUZbDoCw=',
                'descripcion' => 'PERMISO PARA CREACION DE USUARIOS',
                'id_modulo' => 1,
                'created_at' => Carbon::create('2024', '09', '24', '23', '16', '15'),
                'updated_at' => Carbon::create('2024', '09', '24', '23', '16', '15'),
            ],
            [
                'id' => 2,
                'nombre' => 'LkJl3vzFb/ZnrQGybcfDpCJ/f/7YeU0qv1q7IhkFu8A=',
                'descripcion' => 'VER DETALLES DE EMPRESA',
                'id_modulo' => 2,
                'created_at' => Carbon::create('2024', '09', '24', '23', '17', '35'),
                'updated_at' => Carbon::create('2024', '09', '24', '23', '17', '35'),
            ],
            [
                'id' => 3,
                'nombre' => 'QNjdj/c7PAE+UYrvoWs/4NE77/DyAkSH+GRp14mL7/A=',
                'descripcion' => 'AGREGAR NUEVA EMPRESA',
                'id_modulo' => 2,
                'created_at' => Carbon::create('2024', '09', '24', '23', '19', '19'),
                'updated_at' => Carbon::create('2024', '09', '24', '23', '19', '19'),
            ],
            [
                'id' => 4,
                'nombre' => 'u4okdZUEmGcldqTEn+ieyqLJKjzmTfpxvZB+Ql5G8bEi+5tQzsZxdISvIXP0h907',
                'descripcion' => 'PERMISO PARA VER INCAPACIDADES',
                'id_modulo' => 4,
                'created_at' => Carbon::create('2024', '09', '24', '23', '20', '29'),
                'updated_at' => Carbon::create('2024', '09', '24', '23', '20', '29'),
            ],
            [
                'id' => 5,
                'nombre' => 'rjhT3i4jDCm9sl6qNxIlyrghqcp4tmhEL9W+0rukSYQ=',
                'descripcion' => 'PERMISO PARA VER REPORTERIA DE INCAPACIDADES',
                'id_modulo' => 4,
                'created_at' => Carbon::create('2024', '09', '24', '23', '20', '29'),
                'updated_at' => Carbon::create('2024', '09', '24', '23', '20', '29'),
            ],
        ]);
    }
}
