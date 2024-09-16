<?php
declare(strict_types = 1);

namespace App;

class Helpers{
    public static function getRandomString(): string{
        $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($str), 0, 15);
    }

    public static function sanitizeString(string $str): string{
        $str = trim($str);
        $str = htmlentities($str);
        return $str;
    }
}