<?php
declare(strict_types = 1);

namespace App\Controller;

class BaseController{
    protected function redirect(string $url){
        header("Location: $url");
    }
}