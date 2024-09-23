<?php
declare(strict_types = 1);

namespace App\Model;

use App\Database;
use App\Email;
use App\Helpers;

class User{
    protected ?int $id;

    protected string $firstname;

    protected string $lastname;

    protected string $email;

    protected string $password;

    protected bool $isEmailVerified;

    public function __construct(
            ?int $id, 
            string $firstname, 
            string $lastname, 
            string $email, 
            string $password, 
            bool $isEmailVerified = false){
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->isEmailVerified = $isEmailVerified;
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(?int $id){
        $this->id = $id;
    }

    public function getFirstname(): string{
        return $this->firstname;
    }

    public function setFirstname(string $firstname){
        $this->firstname = $firstname;
    }

    public function getLastname(): string{
        return $this->lastname;
    }

    public function setLastname(string $lastname){
        $this->lastname = $lastname;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setEmail(string $email){
        $this->email = $email;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function setPassword(string $password){
        $this->password = $password;
    }

    public function getIsEmailVerified(): bool{
        return $this->isEmailVerified;
    }

    public function setIsEmailVerified(bool $isEmailVerified){
        $this->isEmailVerified = $isEmailVerified;
    }

    public function save(string $emailVerificationToken): bool{
        if($this->id !== null)
            return $this->updateUser($emailVerificationToken);
        else
            return $this->createUser($emailVerificationToken);
    }

    protected function updateUser(string $emailVerificationToken): bool{
        $sql = "UPDATE user SET fname = ?, lname = ?, email = ?, email_verification_token = ?, email_verified = ?, password = ? WHERE id = ?";
        $params = [
            $this->firstname,
            $this->lastname,
            $this->email,
            $emailVerificationToken,
            $this->isEmailVerified,
            $this->password,
            $this->id
        ];

        // $this->sendVerificationEmail($token);
        return Database::getInstance()->execute($sql, $params);
    }

    protected function createUser(string $emailVerificationToken): bool{
        $sql = "INSERT INTO user (fname, lname, email, email_verification_token, password) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $this->firstname, 
            $this->lastname,
            $this->email,
            $emailVerificationToken,
            $this->password
        ];
        $database = Database::getInstance();
        $isUserCreated = $database->execute($sql, $params);
        if(!$isUserCreated)
            return false;

        $this->id = $database->getLastInsertedId();
        // $this->sendVerificationEmail($token, $this);
        return true;
    }

    public function delete(): bool{
        $sql = "DELETE FROM user WHERE id = :id";
        return Database::getInstance()->execute($sql, [':id' =>$this->id]);
    }

    public static function generateEmailVerificationToken(): string{
        return Helpers::getRandomString();
    }

    /** 
     * Verify the user's email address using a unique token. 
     */
    public static function verifyEmailUsingToken(string $email, string $token): bool{
        $database = Database::getInstance();
        $sql = "SELECT email FROM user WHERE email = ? AND email_verification_token = ?";
        $params = [
            $email,
            $token
        ];

        $res = $database->fetchAll($sql, $params);
        if(count($res) < 1)
            return false;

        $sql = "UPDATE user SET email_verified = ? WHERE email = ?";
        if(!$database->execute($sql, [true, $email]))
            return false;
        return true;
    }

    public function sendRegistrationEmail(){
        Email::sendRegistrationEmail($this);
    }

    public function sendVerificationEmail(string $token){
        Email::sendEmailVerification($this, $token, EMAIL_VERIFICATION_DOMAIN);
    }

    public function sendUserDeletionEmail(){
        Email::sendUserDeletionEmail($this);
    }

    public static function getUserByEmail(string $email): ?static{
        $sql = "SELECT * FROM user WHERE email = ?";
        $params = [$email];

        $res = Database::getInstance()->fetchAll($sql, $params);
        if(!(count($res) > 0))
            return null;
        return User::convertDBToUserObj($res[0]);
    }

    public static function getUserById(int $userId): ?static{
        $sql = "SELECT * FROM user WHERE id = ?";
        $database = Database::getInstance();
        $res = $database->fetchAll($sql, [$userId]);
        if(count($res) < 0)
            return null;
        return User::convertDBToUserObj($res[0]);
    }

    /**
     * Convert the user array from a database query to a User instance.
     */
    protected static function convertDBToUserObj(array $dbUser): User{
        return new User(
            id: (int) $dbUser['id'],
            firstname: $dbUser['fname'],
            lastname: $dbUser['lname'],
            email: $dbUser['email'],
            password: $dbUser['password'],
            isEmailVerified: (bool) $dbUser['email_verified']
        );
    }

    /**
     * Get the email verification token for a given user.
     * 
     * @param User $user User to get verification email from.
     * @return string The verification token.
     */
    public static function getVerificationToken(User $user): string{
        $database = Database::getInstance();
        $sql = "SELECT email_verification_token FROM user WHERE id = ?";
        $res = $database->fetchAll($sql, [$user->getId()]);
        return $res[0]['email_verification_token'];
    }
}