<?php

namespace App\Services\Plataforma;

use App\Models\Administracion\LinkServicio;

class SocketService
{
    // Add your service logic here
    public function url_server_socket()
    {
        $servicio = LinkServicio::where('nombre_servicio', 'SERVER_SOCKET_IO')->first();

        if (!$servicio) {
            abort(404, 'no se encontro la direccion del servidor socket');
        }
        $url_server_socket = $servicio->link_servicio;
        return $url_server_socket;
    }

    public function url_server_socket_2()
    {
        $servicio = LinkServicio::where('nombre_servicio', 'SERVER_SOCKET_IO')->first();

        if (!$servicio) {
            return null;
        }
        $url_server_socket = $servicio->link_servicio;
        return $url_server_socket;
    }

    // protected static function booted()
    // {
    //     static::creating(function ($agenda) {
    //         $servicio = LinkServicio::where('link_servicio', 'SERVER_SOCKET_IO')->first();
    //         // if(!$url_server_socket)
    //         $url_server_socket = $servicio->link_servicio;

    //         try {
    //             $response = Http::post($url_server_socket . '/agenda-socket', [
    //                 'message' => "Creación de registro de agenda",
    //                 'data' => $agenda
    //             ]);

    //             // Verificar si la respuesta fue exitosa
    //             if ($response->status() == 500) {
    //                 throw new \Exception("Error en la comunicacion con el socket. Código de respuesta: " . $response->status());
    //             }
    //         } catch (\Exception $e) {
    //             // Loguear el error para diagnóstico
    //             Log::error("Error al intentar conectar con el socket: " . $e->getMessage());

    //             // Lanzar una excepción para interrumpir la creación del registro
    //             throw new \Exception("No se pudo completar la creacion del registro debido a un error con el socket.");
    //         }
    //     });
    //     try {
    //         //code...
    //         static::updating(function ($agenda) {
    //             // Lógica después de actualizar
    //             $url_server_socket = config('app.host_server_socket_io');
    //             $response = Http::post($url_server_socket . '/agenda-socket', [
    //                 'message' => "actualizacion de registro de agenda",
    //                 'data' => $agenda
    //             ]);
    //             if ($response->status() == 500) {
    //                 throw new \Exception("Error en la comunicacion con el socket. Código de respuesta: " . $response->status());
    //             }
    //             // Log::info("Agenda actualizada: " . $agenda->id);
    //         });
    //     } catch (\Exception $e) {
    //         // Loguear el error para diagnóstico
    //         Log::error("Error al intentar conectar con el socket: " . $e->getMessage());

    //         // Lanzar una excepción para interrumpir la creación del registro
    //         throw new \Exception("No se pudo completar la actualizacion del registro debido a un error con el socket.");
    //     }
    // }
}
