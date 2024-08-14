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

    public static function NoteValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data['content'])) {
            $errors[] = "Content is required";
        }
        if (key_exists('color', $data)) {
            if (!preg_match('/^[a-fA-F0-9]{6}$/', $data['color'])) {
                $errors[] = "Invalid hex-color format";
            }
        }
        return $errors;
    }
}
