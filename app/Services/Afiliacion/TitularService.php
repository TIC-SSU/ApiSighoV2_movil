<?php

namespace App\Services\Afiliacion;

use App\Models\Afiliacion\Titular;
use App\Services\ImageService;
use Illuminate\Support\Facades\URL;

class TitularService
{
    // Add your service logic here
    protected $imagenService;

    public function __construct(ImageService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    public function imagen_titular($id_titular)
    {
        $titular = Titular::find($id_titular);
        // dd($titular);
        if (!$titular) {
            abort(404, 'Titular no encontrado');
        }
        $nombre_imagen = $titular->foto_nombre;
        $direccionCarpeta = 'Afiliacion/Fotos/';
        // dd($direccionCarpeta, $nombre_imagen);
        return $this->imagenService->obtener_imagen($nombre_imagen, $direccionCarpeta);
    }
}
