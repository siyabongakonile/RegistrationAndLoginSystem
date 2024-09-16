<?php
declare(strict_types = 1);

namespace App;

class Session{
    private static ?Session $session = null;

    private function __construct(){
        session_start();
    }

    private function __wakeup(){}

    public static function getInstance(): static{
        if(static::$session === null)
            static::$session = new static();
        return static::$session;
    }

    public function set(string $key, string|bool|null|int|float $value){
        $_SESSION[$key] = $value;
    }

    public function get($key): string|bool|null|int|float{
        if(!isset($_SESSION[$key]))
            return null;
        return $_SESSION[$key];
    }

    public function isLoggedIn(): bool{
        if((isset($_SESSION['user-id']) && isset($_SESSION['is-logged-in'])) && $_SESSION['is-logged-in'] === true)
            return true;
        return false;
    }

    public function setIsLoggedIn(int $userId){
        $this->set('user-id', $userId);
        $this->set('is-logged-in', true);
    }

    public function logout(){
        session_unset();
        session_destroy();
    }
}