<?php
declare(strict_types = 1);

namespace App;

class Criptography{
    public static function encryptPassword(string $password): string{
        return password_hash($password, PASSWORD_ARGON2I);
    }

    public static function verifyPassword(string $password, string $hashedPassword): bool{
        return password_verify($password, $hashedPassword);
    }
}