<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\VerificarCachePoblacionAsegurada;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EjecutarEventoAgendaWebCache /* implements ShouldQueue */
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Ejecutando Evento: VerificarCachePoblacionAsegurada a  ' . Carbon::now());
        event(new VerificarCachePoblacionAsegurada('poblacion_asegurada', 'especialidades', 'especialistas', 'horarios'));
    }
}
