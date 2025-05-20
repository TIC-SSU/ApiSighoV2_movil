<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbTest extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Prueba la conexión a la base de datos';

    public function handle()
    {
        $this->info('Probando conexión a la base de datos...');

        try {
            DB::connection()->getPdo();
            $this->info('Conexión a la base de datos exitosa.');
        } catch (\Exception $e) {
            $this->error('Error al conectar con la base de datos: ' . $e->getMessage());
        }
    }
}
