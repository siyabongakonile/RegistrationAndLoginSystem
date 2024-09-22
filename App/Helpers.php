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

    public static function validateEmail(string $email): string|false{
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidPassword(string $password): bool{
        $passlen = strlen($password);
        if($passlen > 4 && $passlen < 20){
            return true;
        }
        return false;
    }
}