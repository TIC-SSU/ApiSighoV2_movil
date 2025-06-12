<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ImageService
{
    // Add your service logic here
    public function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto_nombre = base64_encode($imageData);
                if ($foto_nombre === '') {
                    return null;
                } else {
                    return $foto_nombre;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function obtener_imagen($idFoto, string $direccionCarpeta)
    {
        try {
            if ($idFoto == null) {
                $defaultPath = public_path('img/default_image.png');

                if (!file_exists($defaultPath)) {
                    abort(404, 'Imagen no encontrada y no hay imagen por defecto');
                }
                $imageData = file_get_contents($defaultPath);
            } else {
                $path = $direccionCarpeta . $idFoto;

                if (Storage::disk('ftp')->exists($path)) {
                    $imageData = Storage::disk('ftp')->get($path);
                } else {
                    // Cargar imagen por defecto desde el disco local (public/img/default_image.png)
                    $defaultPath = public_path('img/default_image.png');

                    if (!file_exists($defaultPath)) {
                        abort(404, 'Imagen no encontrada y no hay imagen por defecto');
                    }

                    $imageData = file_get_contents($defaultPath);
                }
            }

            $mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $imageData);

            return response($imageData, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $idFoto . '"')
                ->header('Cache-Control', 'public, max-age=86400'); // 1 día
        } catch (\Throwable $th) {
            abort(500, 'Error al obtener imagen: ' . $th->getMessage());
        }
    }
    public function url_imagen($id, string $ruta, string $nombreParametro = 'id')
    {
        // dd($id, $ruta);
        $imagenUrl = URL::temporarySignedRoute(
            $ruta,        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            [$nombreParametro => $id]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    }
    public function obtener_imagen_old(string $idFoto, string $direccionCarpeta)
    {
        try {
            $path = $direccionCarpeta . $idFoto;
            // dd($path);
            // dd(Storage::disk('ftp')->exists($path));
            if (!Storage::disk('ftp')->exists($path)) {
                abort(404, 'Imagen no encontrada'); // Imagen no encontrada
            }

            $imageData = Storage::disk('ftp')->get($path);
            // dd($imageData);
            $mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $imageData);

            return response($imageData, 200)->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $idFoto . '"')
                ->header('Cache-Control', 'public, max-age=86400'); // 1 día;
        } catch (\Throwable $th) {
            abort(500, $th->getMessage()); // Error interno del servidor
        }
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
