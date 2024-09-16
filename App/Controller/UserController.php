<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Helpers;
use App\Session;
use App\View;
use App\Model\User;

class UserController extends BaseController{
    public function getProfile(){
        $session = Session::getInstance();
        if(!$session->isLoggedIn())
            header("Location: /login");
        View::render('profile');
    }

    public function getEditUserProfile(){
        View::render('edit-profile');
    }

    public function editUserProfile(){

    }

    public function verifyEmail(){
        $email = Helpers::sanitizeString($_GET['email']) ?? '';
        $token = Helpers::sanitizeString($_GET['token']) ?? '';

        if(User::verifyEmailUsingToken($email, $token))
            View::render('auth/login', ['message' => 'Email verified successfully.']);
        else
            View::render('auth/login', ['message' => 'Invalid Email\token for email verification.']);
    }
}