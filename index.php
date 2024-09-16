<?php
declare(strict_types = 1);

use App\Controller\AuthController;
use App\Controller\UserController;
use App\Router;

require 'autoload.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router = new Router();

$router->get('/login', AuthController::class, 'getLogin')
    ->post('/login', AuthController::class, 'login')
    ->get('/register', AuthController::class, 'getRegister')
    ->post('/register', AuthController::class, 'register')
    ->get('/logout', AuthController::class, 'logout')
    ->get('/user', UserController::class, 'getProfile')
    ->get('/user/edit', UserController::class, 'getEditUserProfile')
    ->post('/user/edit', UserController::class, 'editUserProfile')
    ->get('/user/delete', UserController::class, 'deleteUser')
    ->get('/verify', UserController::class, 'verifyEmail');
$router->dispatch($url, $method);