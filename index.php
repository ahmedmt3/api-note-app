<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handelError");
set_exception_handler("ErrorHandler::handelException");

header("Content-type: Application/json; charset=UTF-8;");

$parts = explode('/', $_SERVER['REQUEST_URI']);

if ($parts[2] !== 'notes') {
    http_response_code(404);
    exit;
}

$id = $parts[3] ?? null;

$database = new Database('localhost', 'notes_app', 'root', '');

$notesGateway = new NoteGateway($database);

$controller = new NoteController($notesGateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
