<?php
# For loading classes on demand.
spl_autoload_register(function($class){
    # Convert the class name to the file that contains the class and include it.
    $pathToClass = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
    if(file_exists($pathToClass))
        include_once $pathToClass;

    
});

# For loading files that should be included on start up.
$pathToLoadFrom = __DIR__ . DIRECTORY_SEPARATOR . 'inc';
if(!file_exists($pathToLoadFrom)) 
    return;

$filesToInclude = getFilesFromPath($pathToLoadFrom);
foreach($filesToInclude as $fileToInclude)
    include_once $fileToInclude;

function getFilesFromPath(string $path): array{
    $filesDirs = scandir($path);
    $files = [];

    foreach($filesDirs as $fileDir){
        $fullFileDirPath = $path . DIRECTORY_SEPARATOR . $fileDir;
        if(is_file($fullFileDirPath))
            $files[] = $fullFileDirPath;
    }
    return $files;
}