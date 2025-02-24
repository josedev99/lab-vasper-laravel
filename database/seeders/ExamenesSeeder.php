<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamenesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresaId = 22; // Puedes obtenerlo de .env o definir un valor predeterminado

        $datos = DB::select("SELECT e.nombre as examen,e.descripcion,e.name_tabla,e.usuario_id, c.nombre as categoria FROM `examenes` as e inner join categoria_examens as c on e.categoria_id=c.id where e.empresa_id=12;");//default empresa_id

        foreach ($datos as $dato) {
            $categoriaGetId = DB::table('categoria_examens')->insertGetId([
                'nombre' => $dato->categoria,
                'descripcion' => '',
                'empresa_id' => $empresaId,
                'usuario_id' => $dato->usuario_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            DB::table('examenes')->insert([
                'nombre' => $dato->examen,
                'descripcion' => $dato->descripcion,
                'name_tabla' => $dato->name_tabla,
                'categoria_id' => $categoriaGetId,
                'empresa_id' => $empresaId,
                'usuario_id' => $dato->usuario_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        }
    }
}
