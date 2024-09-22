<?php
declare(strict_types = 1);

namespace App;

use \App\Model\User;

class Email{
    static string $headers = "From: noreply@reglog.user\r\n";

    public static function sendEmailVerification(User $user, string $token, string $link): bool{
        $sendEmailTo = $user->getEmail();
        $emailSubject = APP_NAME . ': Email Verification';
        $firstname = $user->getFirstname();
        $completeLink = static::createCompleteEmailVerificationLink($link, $sendEmailTo, $token);
        $message = <<<_TEXT
Hello $firstname,
Your email address verification link is $completeLink.

Thank you.
_TEXT;
        \Logging\logInfo("Sending Email");
        return mail($sendEmailTo, $emailSubject, $message, static::$headers);
    }

    public static function sendRegistrationEmail(User $user): bool{
        $sendEmailTo = $user->getEmail();
        $emailSubject = APP_NAME . ": Registration";
        $firstname = $user->getFirstname();
        $message = <<<_TEXT
Hi $firstname, 
welcome to RegLog.user! 

Thank you.
_TEXT;
        \Logging\logInfo("Sending Registration Email");
        return mail($sendEmailTo, $emailSubject, $message, static::$headers);
    }

    public static function sendUserDeletionEmail(User $user): bool{
        $sendEmailTo = $user->getEmail();
        $emailSubject = APP_NAME . ": Account Deletion";
        $firstname = $user->getFirstname();
        $message = <<<_TEXT
Good day $firstname,
sad to see you leave. Your account has been deleted successfully.

Thank you.
_TEXT;
        \Logging\logInfo("Sending Deletion");
        return mail($sendEmailTo, $emailSubject, $message, static::$headers);
    }

    public static function sendEmailUpdateEmail(User $user, string $emailVerificationToken, string $verificationEndpoint): bool{
        $sendEmailTo = $user->getEmail();
        $emailSubject = APP_NAME . ": Email Update";
        $firstname = $user->getFirstname();
        $completeLink = static::createCompleteEmailVerificationLink($verificationEndpoint, $sendEmailTo, $emailVerificationToken);
        $message = <<<_TEXT
Hello $firstname,
an update on you email address on our platform was made 
and you can verify your new address using this link: $completeLink.
_TEXT;
        \Logging\logInfo("Sending email update email");
        return mail($sendEmailTo, $emailSubject, $message, static::$headers);
    }

    protected static function createCompleteEmailVerificationLink(string $verificationEndpoint, string $userEmail, string $verificationToken): string{
        return "{$verificationEndpoint}?email={$userEmail}&token={$verificationToken}";
    }
}