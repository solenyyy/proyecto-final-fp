<?php

use App\Kernel;

header('Access-Control-Allow-Origin: https://proyecto-final-fp-five.vercel.app');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

$autoload = dirname(__DIR__).'/vendor/autoload_runtime.php';

if (!file_exists($autoload)) {
    $autoload = dirname(__DIR__).'/vendor/autoload.php';
}

require_once $autoload;

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
