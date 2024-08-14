<?php

namespace App\Controllers;

class UrlController{

    private static $id;

    public static function checkUrl(string $url): void
    {
        $parts = explode('/', $url);

        if ($parts[2] !== 'notes' && $parts[2] !== 'users') {
            http_response_code(404);
            echo json_encode(["error" => "Not Found"]);
            exit;
        }

        self::$id = $parts[3] ?? null;
    }

    public static function getId(): string | null
    {
        return self::$id;
    }
}