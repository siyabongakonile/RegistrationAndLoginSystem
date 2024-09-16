<?php
declare(strict_types = 1);

namespace App;

class View{
    private static string $viewExt = '.php';

    public static function render(string $view, array $args = []){
        $view = str_replace('/', DIRECTORY_SEPARATOR, $view);
        $pathToView = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . static::$viewExt;
        if(!file_exists($pathToView))
            throw new \Exception("View {$view} not found.");

        extract($args); 
        include $pathToView;
    }
}