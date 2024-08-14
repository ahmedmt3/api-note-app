<?php

declare(strict_types=1);

namespace App;

use App\Config\Config;
use App\Config\Database;
use App\Controllers\NoteController;
use App\Controllers\UserController;
use App\Services\NoteServices;
use App\Services\UserServices;
use App\Utils\ErrorHandler;
use App\Utils\Helpers;

// Auto Loading Classes
require __DIR__ . "/src/Config/autoloader.php";

// Setting error and exception handlers
set_error_handler([ErrorHandler::class, 'handelError']);
set_exception_handler([ErrorHandler::class, 'handelException']);

header("Content-type: Application/json; charset=UTF-8");

$end_point = Helpers::get_end_point($_SERVER['REQUEST_URI']);
$id        = Helpers::getId($_SERVER['REQUEST_URI']);

$database = new Database(Config::DB_CONFIG);

switch ($end_point) {
    case 'notes':
        $noteServices = new NoteServices($database);
        $controller = new NoteController($noteServices);
        break;

    case 'users':
        $userServices = new UserServices();
        $controller = new UserController($userServices);
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Not Found"]);
        exit;
}

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
