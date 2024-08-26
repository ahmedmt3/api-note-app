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

    public static function noteValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if (empty($data)) {
            $errors[] = "Data is empty or not provided";
        }

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

    public static function userValidationErrors(array $data, bool $is_signup = false): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = "Username is required";
        }
        if (empty($data['email']) && $is_signup) {
            $errors[] = "Email is required";
        }
        if (empty($data['password'])) {
            $errors[] = "Password is required";
        }

        return $errors;
    }
}
