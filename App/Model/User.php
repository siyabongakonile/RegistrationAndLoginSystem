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

    public function save(): bool{
        if($this->id !== null)
            return $this->updateUser();
        else
            return $this->createUser();
    }

    protected function updateUser(): bool{
        $sql = "UPDATE user SET fname = ?, lname = ?, email = ?, password = ? WHERE id = ?";
        $params = [
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->password,
            $this->id
        ];
        return Database::getInstance()->execute($sql, $params);
    }

    protected function createUser(): bool{
        $token = $this->generateEmailVerificationToken();
        $sql = "INSERT INTO user (fname, lname, email, email_verification_token, password) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $this->firstname, 
            $this->lastname,
            $this->email,
            $token,
            $this->password
        ];
        $database = Database::getInstance();
        $isUserCreated = $database->execute($sql, $params);
        if(!$isUserCreated)
            return false;

        $this->id = $database->getLastInsertedId();
        $this->sendVerificationEmail($token);
        return true;
    }

    public function delete(): bool{
        $sql = "DELETE FROM user WHERE id = :id";
        return Database::getInstance()->execute($sql, [':id' =>$this->id]);
    }

    protected function generateEmailVerificationToken(): string{
        return Helpers::getRandomString();
    }

    public static function verifyEmailUsingToken(string $email, string $token): bool{
        $database = Database::getInstance();
        $sql = "SELECT email, token FROM user WHERE email = ? AND email_verification_token = ?";
        $params = [
            $email,
            $token
        ];

        $res = $database->fetchAll($sql, $params);
        if(count($res) < 1)
            return false;

        $sql = "UPDATE user SET email_verified = ?";
        if(!$database->execute($sql, [true]))
            return false;
        return true;
    }

    public function sendVerificationEmail(string $token){
        Email::sendEmailVerification($token);
    }

    public static function getUserByEmail(string $email): ?static{
        $sql = "SELECT * FROM user WHERE email = ?";
        $params = [$email];

        $res = Database::getInstance()->fetchAll($sql, $params);
        if(!(count($res) > 0))
            return null;
        $row = $res[0];
        return new static(
            $row['id'], 
            $row['fname'], 
            $row['lname'], 
            $row['email'], 
            $row['password'], 
            (bool) $row['email_verified']
        );
    }
}