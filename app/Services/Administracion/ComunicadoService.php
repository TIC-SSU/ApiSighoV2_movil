<?php

namespace App\Services\Administracion;

use App\Data\Administracion\ComunicadoDTOData;
use App\Models\Administracion\Comunicado;
use App\Models\Plataforma\Comunicados;

class ComunicadoService
{
    // Add your service logic here

    public function listar_comunicados()
    {
        $comunicado = Comunicados::get();
        if (!$comunicado || $comunicado->isEmpty()) {
            abort(404, 'No se encontraron comunicados');
        }
        return $comunicado;
    }
    public function registrar_comunicado(ComunicadoDTOData $data)
    {
        // dd($data);
        $comunicado = Comunicado::create([
            'nro_comunicado'     => $data->nro_comunicado,
            'comunicado'         => $data->comunicado,
            'fecha_inicio'       => $data->fecha_inicio,
            'fecha_fin'          => $data->fecha_fin,
            'id_usuario_emisor'  => $data->id_usuario_emisor,
            'estado'             => $data->estado ?? true,
            'id_user_created'    => $data->id_user_created,
            'id_user_updated'    => $data->id_user_updated,
        ]);
        if (!$comunicado) {
            abort(422, 'No se creo el comunicado');
        }
        return $comunicado;
    }
}
