<?php

namespace App\Utils;

use ErrorException;
use Throwable;

class ErrorHandler
{

    public static function handelException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()

        ]);
    }

    public static function handelError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline,
    ): bool {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
