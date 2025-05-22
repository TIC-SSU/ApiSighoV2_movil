<?php

namespace App\Models\Plataforma;

use App\Models\Administracion\LinkServicio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\Persona;

use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\ServicioPlataforma;

use App\Models\Plataforma\SuspensionHorario;

class Agenda extends Model
{
    use HasFactory;
    protected $table = "plataforma.agenda";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_agenda',
        'hora_agenda',
        'confirmado',
        'id_servicio_plataforma',
        'id_persona',
        'id_asignacion_horario',
        'anulacion_ficha',
        'fecha_anulacion',
        'motivo_anulacion',
        'id_suspension_horario',
        'clave_unica',
        'ficha_extra',
        'id_user_created',
        'id_user_updated',
    ];

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
    // protected static function booted()
    // {
    //     static::created(function ($agenda) {
    //         $url_server_socket = config('app.host_server_socket_io');
    //         // $url_server_socket = 'http://192.168.1.84:6001';
    //         // $url_server_socket = 'http://192.168.4.230:6001';

    //         // try {
    //         // $response=
    //         Http::post($url_server_socket . '/agenda-socket', [
    //             'message' => "Creacion de registro de agenda",
    //             'data' => $agenda
    //         ]);

    //         // Verificar si la respuesta fue exitosa
    //         //     if ($response->status() == 500) {
    //         //         throw new \Exception("Error en la comunicacion con el socket. Código de respuesta: " . $response->status());
    //         //     }
    //         // } catch (\Exception $e) {
    //         //     // Loguear el error para diagnóstico
    //         //     Log::error("Error al intentar conectar con el socket: " . $e->getMessage());

    //         //     // Lanzar una excepción para interrumpir la creación del registro
    //         //     throw new \Exception("No se pudo completar la creacion del registro debido a un error con el socket.");
    //         // }
    //     });
    // }
    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioAgenda()
    {
        return $this->belongsTo(AsignacionHorario::class, 'id_asignacion_horario', 'id');
    }

    //Relaciones con la tabla Servicio Plataforma
    public function servicioPlataformaAgenda()
    {
        return $this->belongsTo(ServicioPlataforma::class, 'id_servicio_plataforma', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaAgenda()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Suspension Horario
    public function suspensionHorarioAgenda()
    {
        return $this->belongsTo(SuspensionHorario::class, 'id_suspension_horario', 'id');
    }



    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
