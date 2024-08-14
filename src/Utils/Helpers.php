<?php

namespace App\Utils;

class Helpers
{

    public static function get_end_point(string $url): string
    {
        $Urlparts = explode('/', $url);
        return $Urlparts[2];
    }
    public static function getId(string $url): string | null
    {
        $Urlparts = explode('/', $url);
        return $Urlparts[3] ?? null;
    }
}
