<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class CheckRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test de conexion con redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $pong = Redis::ping();
            $this->info("Redis está activo: {$pong}");
        } catch (\Exception $e) {
            $this->error("Redis no responde: " . $e->getMessage());
        }
    }
}
