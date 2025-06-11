<?php

namespace App\Services;

use App\Models\User;
use App\Services\ImageService;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UserService
{
    protected $imagenService;

    public function __construct(ImageService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    // Add your service logic here
    public function obtenerPoblacionAseguradaCache()
    {
        $cache = Cache::get('poblacion_asegurada');
        // dd('servicio');
        return $cache;
    }
    // Add your service logic here
    public function getAllUsers()
    {
        return User::all();
    }
    public function obtener_imagen_usuario($id_user)
    {
        $user = User::find($id_user);
        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }
        $nombre_imagen = $user->imagen;
        $direccion_foto = 'Administracion/Usuarios/Perfil/';
        return $this->imagenService->obtener_imagen($nombre_imagen, $direccion_foto);
    }
    public function url_imagen($id_user)
    {
        $imagenUrl = URL::temporarySignedRoute(
            'obtener_imagen_usuario',        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            ['id_user' => $id_user]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    }
}
