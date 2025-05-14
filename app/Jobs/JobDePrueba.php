<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Queueable; // <- Faltaba esta línea

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\EventoDePrueba;
use Illuminate\Support\Facades\Log;

class JobDePrueba /* implements ShouldQueue */
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
        Log::info('🚀 Job ejecutado, lanzando evento de prueba...');
        event(new EventoDePrueba('Este es un mensaje de prueba para validar el sistema.'));
    }
}
