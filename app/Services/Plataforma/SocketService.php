<?php

namespace App\Services\Plataforma;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    private function url_server_socket_2()
    {
        $servicio = LinkServicio::where('nombre_servicio', 'SERVER_SOCKET_IO')->first();

        if (!$servicio) {
            return null;
        }
        $url_server_socket = $servicio->link_servicio;
        return $url_server_socket;
    }

    public function agregar_agenda($agenda)
    {


        try {
            $url_server_socket = $this->url_server_socket_2();
            $response = Http::post($url_server_socket . '/agenda-socket', [
                'message' => "Creación de registro de agenda",
                'data' => $agenda
            ]);

            // Verificar si la respuesta fue exitosa
            if ($response->status() == 500) {
                throw new \Exception("Error en la comunicacion con el socket. Código de respuesta: " . $response->status());
            }
        } catch (\Exception $e) {
            // Loguear el error para diagnóstico
            Log::error("Error al intentar conectar con el socket: " . $e->getMessage());

            // Lanzar una excepción para interrumpir la creación del registro
            throw new \Exception("No se pudo completar la creacion del registro debido a un error con el socket.");
        }
    }
}
