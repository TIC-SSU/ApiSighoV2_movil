<?php

namespace App\Listeners;

use App\Events\EventoDePrueba;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ListenerDePrueba
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
    public function handle(EventoDePrueba $event)
    {
        Log::info('✅ Listener ejecutado! Mensaje recibido: ' . $event->mensaje);
    }
}
