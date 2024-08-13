<?php

declare(strict_types=1);

namespace App;

use App\Config\Config;
use App\Config\Database;
use App\Controllers\NoteController;
use App\Services\NoteServices;
use App\Utils\ErrorHandler;

// Auto Loading Classes
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    //Check if starts with App\ or not
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Setting error and exception handlers
set_error_handler([ErrorHandler::class, 'handelError']);
set_exception_handler([ErrorHandler::class, 'handelException']);

header("Content-type: Application/json; charset=UTF-8");

Config::checkUrl($_SERVER['REQUEST_URI']);


$db_host = Config::DB_HOST;
$db_name = Config::DB_NAME;
$db_user = Config::DB_USER;
$db_pass = Config::BD_PASS;

$database = new Database($db_host, $db_name, $db_user, $db_pass);

$noteServices = new NoteServices($database);

$controller = new NoteController($noteServices);

$controller->processRequest($_SERVER['REQUEST_METHOD'], Config::getId());
