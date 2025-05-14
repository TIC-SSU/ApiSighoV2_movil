<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear servicio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener el nombre del servicio
        $serviceName = $this->argument('name');

        // Definir la ruta base de los servicios
        $servicePath = app_path('Services');

        // Si el nombre incluye una subcarpeta
        if (strpos($serviceName, '/') !== false) {
            // Separar la subcarpeta del nombre del servicio
            $subFolders = explode('/', $serviceName);
            $serviceName = array_pop($subFolders); // El último elemento es el nombre del servicio
            $servicePath .= '/' . implode('/', $subFolders); // Construir la ruta de la subcarpeta
        }

        // Crear la carpeta (y subcarpetas) si no existe
        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true); // El tercer parámetro (true) crea subcarpetas recursivamente
        }

        // Definir la ruta completa del archivo del servicio
        $serviceFilePath = $servicePath . '/' . $serviceName . '.php';

        // Verificar si el archivo ya existe
        if (File::exists($serviceFilePath)) {
            $this->error("Service {$serviceName} already exists!");
            return;
        }

        // Crear el contenido del servicio
        $namespace = 'App\\Services';
        if (isset($subFolders)) {
            $namespace .= '\\' . implode('\\', $subFolders); // Agregar subcarpetas al namespace
        }

        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "class {$serviceName}\n";
        $content .= "{\n";
        $content .= "    // Add your service logic here\n";
        $content .= "}\n";

        // Guardar el archivo del servicio
        File::put($serviceFilePath, $content);

        // Mostrar la ruta del archivo generado
        $this->info("Service {$serviceName} created successfully!");
        $this->line("<comment>File created:</comment> " . $serviceFilePath);
    }
}
