<?php

namespace App\Data\Administracion;

use Spatie\LaravelData\Data;

class ComunicadoDTOData extends Data
{
    public function __construct(
        //
        public ?int $nro_comunicado,
        public ?string $comunicado,
        public ?string $fecha_inicio,
        public ?string $fecha_fin,
        public ?int $id_usuario_emisor,
        public ?bool $estado,
        public ?int $id_user_created,
        public ?int $id_user_updated,
    ) {}
}
