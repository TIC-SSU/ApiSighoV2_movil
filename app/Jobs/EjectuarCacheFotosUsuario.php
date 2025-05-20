<?php

namespace App\Jobs;

use App\Events\CacheFotosUsuario;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class EjectuarCacheFotosUsuario /* implements ShouldQueue */
{
    use Queueable;

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
        //
        Log::info('Ejecutando Evento: CacheFotosUsuario a  ' . Carbon::now());
        event(new CacheFotosUsuario('fotos_usuario'));
    }
}
