<?php

use App\Events\EventoDePrueba;
use App\Jobs\EjecutarEventoAgendaWebCache;
use App\Jobs\JobDePrueba;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ProcessOrder;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Aquí definimos directamente las tareas programadas, SIN return
// Schedule::call(function () {
//     Log::info('✅ Tarea de prueba ejecutada!---------------- luego viene el job prueba');
// })->everyMinute();


// Schedule::job(new JobDePrueba)->withoutOverlapping();
// $schedule->job(new EjecutarEventoAgendaWebCache)->cron('50 18,19,20,21,22 * * *');
Schedule::job(new EjecutarEventoAgendaWebCache)->withoutOverlapping()/* ->cron('50 18,19,20,21,22 * * *') */;
