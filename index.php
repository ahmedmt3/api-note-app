<?php

declare(strict_types=1);

namespace App;

use App\Config\Config;
use App\Config\Database;
use App\Controllers\NoteController;
use App\Controllers\UrlController;
use App\Services\NoteServices;
use App\Utils\ErrorHandler;

// Auto Loading Classes
require __DIR__ . "/src/Config/autoloader.php";

// Setting error and exception handlers
set_error_handler([ErrorHandler::class, 'handelError']);
set_exception_handler([ErrorHandler::class, 'handelException']);

header("Content-type: Application/json; charset=UTF-8");

UrlController::checkUrl($_SERVER['REQUEST_URI']);

$db_host = Config::DB_HOST;
$db_name = Config::DB_NAME;
$db_user = Config::DB_USER;
$db_pass = Config::BD_PASS;

$database = new Database($db_host, $db_name, $db_user, $db_pass);

$noteServices = new NoteServices($database);

$controller = new NoteController($noteServices);

$controller->processRequest($_SERVER['REQUEST_METHOD'], UrlController::getId());
