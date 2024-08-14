<?php

namespace App\Config;

class Config
{

    // Database Host name
    private const DB_HOST = "localhost";
    // Database Name
    private const DB_NAME = "notes_app";
    // Database username
    private const DB_USER = "root";
    // Database Password
    private const DB_PASS = "";

    public static const DB_CONFIG = [
        'DB_HOST' => $this::DB_HOST,
        'DB_NAME' => $this::DB_NAME,
        'DB_USER' => $this::DB_USER,
        'DB_NAME' => $this::DB_PASS
    ];
}
