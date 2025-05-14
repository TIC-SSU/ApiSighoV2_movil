<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    //
    public function test()
    {
        return response()->json([
            'status' => 200,
            'message' => 'prueba proteccion de rutas , test de conexion'
        ]);
    }
    public function carga_cache()
    {
        $cacheKey = 'titulares_lista';
        $titulares = Titular::all();
        $cache = Cache::put($cacheKey, $titulares);
        // dd($cache);
        if (!$cache) {
            $data = [
                'status' => 404,
                'message' => 'No se econtraron datos de cache',
            ];
            return response()->json($data, 404);
        }
        return response()->json([
            'status' => 201,
            'message' => 'Datos guardados en cache',
            'data' => $titulares,
            'cache_result' => $cache,
        ], 201);
        // return response()->json()
    }
    public function ver_cache()
    {
        $cacheKey = 'titulares_lista';
        $titulares = Cache::get($cacheKey);
        return response()->json($titulares);
        // return response()->json()
    }
    public function obtenerTitularesCache()
    {
        $cacheKey = 'titulares_json';
        $json = Cache::get($cacheKey);

        if (!$json) {
            $titulares = Titular::all();

            if ($titulares->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No hay titulares disponibles',
                ], 404);
            }

            $json = $titulares->toJson();
            Cache::put($cacheKey, $json, 5); // Guardar por 1 hora
        }
        return $json;
    }
    public function almacenar_personas_en_cache()
    {
        // Obtener las personas por página
        $personas = Persona::paginate(100); // Cambia 100 por el número de registros por página que desees

        // Iterar sobre cada página de resultados
        foreach ($personas as $persona) {
            $cacheKey = 'persona:' . $persona->id;
            $json = $persona->toJson();
            Cache::put($cacheKey, $json, 300);
        }

        // Retornar un mensaje de éxito
        return response()->json([
            'status' => 200,
            'message' => 'Datos de personas guardados en cache',
        ]);
    }

    // public function obtener_personas_cache($id_persona)
    // {
    //     $cacheKey = 'persona:' . $id_persona;

    //     // Intentar obtener el registro desde el cache de Redis
    //     $registro = Cache::remember($cacheKey, 300, function () use ($id_persona) {
    //         // Si no está en el cache, obtenerlo de la base de datos
    //         $persona = Persona::find($id_persona);

    //         // Si no se encuentra la persona, retornar null o error
    //         if (!$persona) {
    //             return null; // O lanzar un error si prefieres
    //         }

    //         // Convertir la persona a string JSON
    //         return $persona->toJson(); // Convertimos a string JSON
    //     });

    //     // Si no se encuentra en cache ni en la base de datos
    //     if (!$registro) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'No se encontraron datos de persona',
    //         ], 404);
    //     }

    //     // Convertir el JSON string a un objeto PHP
    //     $personaDecodificada = json_decode($registro);

    //     // Retornar la respuesta como JSON
    //     return response()->json([
    //         'status' => 200,
    //         'data' => $personaDecodificada,
    //     ]);
    // }


    public function obtener_personas_cache($id_persona)
    {
        $cacheKey = 'persona:' . $id_persona;

        // Usar Cache::remember para simplificar la obtención y almacenamiento en cache
        $registro = Cache::remember($cacheKey, 300, function () use ($id_persona) {
            // Si no está en el cache, buscar la persona en la base de datos
            $persona = Persona::find($id_persona);

            if (!$persona) {
                // Si no se encuentra la persona, retornar error 404
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontraron datos de persona',
                ], 404);
            }

            // Convertir la persona a string JSON
            return $persona->toJson();
        });

        // Decodificar el JSON string a un objeto PHP
        $personaDecodificada = json_decode($registro);

        // Retornar la respuesta como JSON
        return response()->json([
            'status' => 200,
            'message' => 'Se encontraron datos de persona',
            'data' => $personaDecodificada, // Aquí devolvemos un objeto, no un string
        ]);
    }

    public function obtener_personas($id_persona)
    {
        // dd($id_persona);
        try {
            $datos = Persona::find($id_persona);
            if (!$datos) {
                $data = [
                    'status' => 404,
                    'message' => 'No se encontraron datos en Persona',
                    'data' => null,
                ];
                return response()->json($data, 404);
            }
            $data = [
                'status' => 200,
                'data' => $datos,
            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            $dataError = [
                'status' => 500,
                'success' => false,
                'message' => 'Error al listar en Persona',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ];
            return response()->json($dataError, 500);
        }
    }
}
