<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Criptography;
use App\Helpers;
use App\Model\User;
use App\Session;
use App\View;

class AuthController extends BaseController{
    public function getRegister(){
        $this->redirectToUserIfLoggedIn();
        View::render('auth/register');
    }
    
    public function register(){
        $fname = Helpers::sanitizeString($_POST['fname']) ?? '';
        $lname = Helpers::sanitizeString($_POST['lname']) ?? '';
        $email = Helpers::sanitizeString($_POST['email']) ?? '';
        $password = Helpers::sanitizeString($_POST['password']) ?? '';
        $errors = [];

        if($fname == '' || $lname == '' || $email == '' || $password == '')
            $errors[] = "All fields are required.";

        if(!filter_var($email, FILTER_VALIDATE_EMAIL) && empty($errors))
            $errors[] = "Enter a valid Email Address.";

        if(empty($errors)){
            $hashedPassword = Criptography::encryptPassword($password);
            $user = new User(null, $fname, $lname, $email, $hashedPassword);
            if($user->save())
                $this->loginUser($user);
            else
                $errors[] = "Error occured while creating user. Please try again later.";
        }

        View::render('auth/register', [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'errors' => $errors
        ]);
    }

    public function getLogin(){
        $this->redirectToUserIfLoggedIn();
        View::render('auth/login');
    }

    public function login(){
        $email = Helpers::sanitizeString($_POST['email']) ?? '';
        $password = Helpers::sanitizeString($_POST['password']) ?? '';
        $errors = [];

        if($email == '' || $password == '')
            $errors[] = "All fields are required.";

        if(!filter_var($email, FILTER_VALIDATE_EMAIL) && empty($errors))
            $errors[] = "Enter a valid Email Address.";

        $user = User::getUserByEmail($email);
        if($user == null && empty($errors))
            $errors[] = "Invalid email or password.";

        $isPasswordCorrect = false;
        if($user instanceof User)
            $isPasswordCorrect = Criptography::verifyPassword($password, $user->getPassword());

        if(!$isPasswordCorrect && empty($errors))
            $errors[] = "Invalid email or password.";

        if(!empty($errors)){
            View::render('auth/login', [
                'email' => $email,
                'errors' => $errors
            ]);
        }
            
        $this->loginUser($user);
    }

    public function logout(){
        Session::getInstance()->logout();
        header("Location: /login");
    }

    protected function redirectToUserIfLoggedIn(){
        $session = Session::getInstance();
        if($session->isLoggedIn())
            header("Location: /user?id=" . $session->get('user-id'));
    }

    protected function loginUser(User $user){
        $session = Session::getInstance();
        $session->setIsLoggedIn($user->getId());
        header("Location: /user?id=" . $user->getId());
    }
}