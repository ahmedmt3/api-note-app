<?php

namespace App\Config;

use PDO;

class Database
{
    private  $host;
    private  $name;
    private  $user;
    private  $pass;

    public function __construct(private array $DB_CONFIG)
    {
        $this->host = $this->DB_CONFIG['DB_HOST'];
        $this->name = $this->DB_CONFIG['DB_NAME'];
        $this->user = $this->DB_CONFIG['DB_USER'];
        $this->pass = $this->DB_CONFIG['DB_PASS'];
    }


    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8;";
        return new PDO($dsn, $this->user, $this->pass);
    }
}
