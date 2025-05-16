<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    //
    protected $imagenService;
    protected $poblacion_asegurada_cache;

    public function __construct(ImageService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    public function mostrar(string $nombreImagen)
    {
        // Aquí defines la carpeta base donde están las imágenes
        $carpeta = 'imagenes/';

        // Delegas al servicio
        return $this->imagenService->obtener_imagen($nombreImagen, $carpeta);
    }
    protected function obtenerPoblacionAseguradaCache()
    {
        $this->poblacion_asegurada_cache = Cache::get('poblacion_asegurada');
    }

    // public function mostrar($filename)
    // {
    //     // Asegúrate de que el disco ftp esté configurado en config/filesystems.php
    //     $disk = Storage::disk('ftp');

    //     if (!$disk->exists($filename)) {
    //         abort(404, 'Imagen no encontrada.');
    //     }

    //     // Obtener el contenido y tipo MIME
    //     $file = $disk->get($filename);
    //     $mimeType = $disk->mimeType($filename);

    //     // Devolver la respuesta con headers adecuados
    //     return response($file, 200)
    //         ->header('Content-Type', $mimeType);
    // }
}
