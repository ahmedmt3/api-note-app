<?php

namespace App\Config;

class Config
{

    // Database Host name
    public const DB_HOST = "localhost";
    // Database Name
    public const DB_NAME = "notes_app";
    // Database username
    public const DB_USER = "root";
    // Database Password
    public const BD_PASS = "";


    private static $id;

    public static function checkUrl(string $url): void
    {
        $parts = explode('/', $url);

        if ($parts[2] !== 'notes') {
            http_response_code(404);
            echo json_encode(["error"=>"Page Not Found"]);
            exit;
        }

        self::$id = $parts[3] ?? null;
    }

    public static function getId(): string | null
    {
        return self::$id;
    }
}
