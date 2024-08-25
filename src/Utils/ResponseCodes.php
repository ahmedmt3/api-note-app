<?php

namespace App\Utils;

class ResponseCodes
{
    public const CREATED = 201;

    public const UNAUTHORIZED = 401;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const CONFLICT = 409;
    public const UNPROCESSABLE_ENTITY = 422;
}