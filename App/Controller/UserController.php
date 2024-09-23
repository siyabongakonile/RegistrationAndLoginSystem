<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Criptography;
use App\Helpers;
use App\Session;
use App\View;
use App\Model\User;

class UserController extends BaseController{
    public function getProfile(){
        $session = Session::getInstance();
        if(!$session->isLoggedIn())
            $this->redirect("/login");
        
        $userId = Session::getInstance()->get('user-id');
        $user = User::getUserById($userId);
        View::render('profile', ['user' => $user]);
    }

    public function getEditUserProfile(){
        $user = User::getUserById(Session::getInstance()->get('user-id'));
        View::render('edit-profile', ['user' => $user]);
    }

    public function editUserProfile(){
        $firstname = Helpers::sanitizeString($_POST['fname']) ?? '';
        $lastname  = Helpers::sanitizeString($_POST['lname']) ?? '';
        $email     = Helpers::sanitizeString($_POST['email']) ?? '';
        $password1 = Helpers::sanitizeString($_POST['password']) ?? '';
        $password2 = Helpers::sanitizeString($_POST['confirm-password']) ?? '';
        $error = '';
        $updatePassword = false;

        if(empty($firstname) || empty($lastname) || empty($email))
            $error = "All feilds must be filled.";

        if(!Helpers::validateEmail($email) && empty($error))
            $error = "Enter a valid email address.";

        if(!empty($password1) || !empty($password2)){
            if($password1 != $password2){
                $error = "The two passwords entered do not match.";
            } else {
                if(Helpers::isValidPassword($password1)){
                    $updatePassword = true;
                } else {
                    $error = "Please enter a password between 4 and 20 characters.";
                }
            }
        }

        if(!empty($error)){
            View::render('edit-profile', ['errors' => [$error]]);
            return;
        }

        $userId = Session::getInstance()->get('user-id');
        $user = User::getUserById($userId);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        if($updatePassword) 
            $user->setPassword(Criptography::encryptPassword($password1));

        if($email != $user->getEmail()){
            $user->setEmail($email);
            $user->setIsEmailVerified(false);
            $emailVerificationToken = $user->generateEmailVerificationToken();
        } else {
            $emailVerificationToken = User::getVerificationToken($user);
        }

        $user->save($emailVerificationToken);

        if(!$user->getIsEmailVerified())
            $user->sendVerificationEmail($emailVerificationToken);
        Session::getInstance()->setMessage("Profile updated");
        $this->redirect('/user');
    }

    public function verifyEmail(){
        $email = Helpers::sanitizeString($_GET['email']) ?? '';
        $token = Helpers::sanitizeString($_GET['token']) ?? '';

        if(User::verifyEmailUsingToken($email, $token))
            $message = 'Email verified successfully.';
        else
            $message = 'Invalid Email\token for email verification.';

        $session = Session::getInstance();
        $session->setMessage($message);
        if(Session::getInstance()->isLoggedIn()){
            $user = User::getUserById($session->get('user-id'));
            $this->redirect('/user');
        } else
            $this->redirect('/login');
    }

    public function sendVerificationEmail(){
        $session = Session::getInstance();
        if(!$session->isLoggedIn())
            $session->setMessage("You need to login to access the email verification page.");
            $this->redirect('/login');

        $userId = $session->get('user-id');
        $user = User::getUserById($userId);
        $user->sendVerificationEmail(User::getVerificationToken($user));
        $session->setMessage("Email verification email sent.");
        $this->redirect('/user');
    }

    public function deleteUser(){
        $session = Session::getInstance();
        if(!$session->isLoggedIn())
            $this->redirect("/login");

        $user = User::getUserById($session->get('user-id'));
        $isUserDeleted = $user->delete();
        if(!$isUserDeleted)
            View::render('profile', ['message' => "Error occured while deleting user."]);

        $user->sendUserDeletionEmail();
        $session->logout();
        $this->redirect('/login');
    }
}