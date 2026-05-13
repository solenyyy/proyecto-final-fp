<?php

use App\Kernel;

// 1. CABECERAS CORS (Indispensable para Vercel)
header('Access-Control-Allow-Origin: https://proyecto-final-fp-five.vercel.app');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

// 2. CARGA DINÁMICA DEL AUTOLOAD
// Probamos la ruta estándar de Symfony
$autoload = dirname(__DIR__).'/vendor/autoload_runtime.php';

if (!file_exists($autoload)) {
    // Si falla, probamos la ruta absoluta del contenedor de Railway
    $autoload = '/app/vendor/autoload_runtime.php';
}

require_once $autoload;

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
