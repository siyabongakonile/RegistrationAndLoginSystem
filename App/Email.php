<?php
declare(strict_types = 1);

namespace App;

use \App\Model\User;

class Email{
    public static function sendEmailVerification(string $token, User $user, string $link): bool{
        $to = $user->getEmail();
        $subject = 'RegLog: Email Verification';
        $helloMsg = "Hello {$user->getFirstname()}";
        $completeLink = "{$link}?email={$user->getEmail()}&token={$token}";
        $message = <<<_TEXT
$helloMsg,
Your email address verification link is $completeLink.

Thank you.
_TEXT;
        \Logging\logInfo("Sending Email");
        return mail($to, $subject, $message);
    }
}