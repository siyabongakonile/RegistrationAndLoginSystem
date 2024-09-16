<?php
declare(strict_types = 1);

namespace App;

class Email{
    public static function sendEmailVerification(string $token): bool{
        $to = '';
        $subject = '';
        $message = <<<_TEXT

_TEXT;

        return mail($to, $subject, $message);
    }
}