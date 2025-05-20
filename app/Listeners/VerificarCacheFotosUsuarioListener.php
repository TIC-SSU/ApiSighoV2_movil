<?php

namespace App\Listeners;

use App\Events\CacheFotosUsuario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VerificarCacheFotosUsuarioListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CacheFotosUsuario $event): void
    {
        //
        $key = $event->cacheKeyFotosUsuario;
        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
    }
}
