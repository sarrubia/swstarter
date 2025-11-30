<?php

namespace App\Lib\Utils;

class ArrayUtils
{
    public static function keyExists(string $key, array $array, mixed $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}
