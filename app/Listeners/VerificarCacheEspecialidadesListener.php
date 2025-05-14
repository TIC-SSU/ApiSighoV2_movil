<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerificarCacheEspecialidadesListener
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
    public function handle(VerificarCachePoblacionAsegurada $event): void
    {
        //
    }
}
