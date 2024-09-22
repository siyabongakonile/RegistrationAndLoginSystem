<?php
declare(strict_types = 1);

namespace Logging;

/**
 * log the message to the log file.
 * 
 * If the log file does not exist try to create it.
 * 
 * @param string $type Type of log message.
 * @param string $message Message to log.
 * @param string|null $file File where the log function was called.
 * @param int|null $line Line number where the log functin was called.
 */
function logMessage(string $type, string $message, ?string $file = null, ?int $line = null){
    $logFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'main.log';
    $extraData = $file !== null? "File: " . $file . ';': '';
    $extraData .= $line !== null? "Line: " . $line . ';': '';
    $extraData = trim($extraData, ';');
    // Date and time for each log entry
    $dateTime = date('Y-m-d H:i:s');

    $message = "[" . $dateTime . "] " . $type . " -- " . $message . ($extraData != ''? " -- ($extraData)": '') . PHP_EOL;
    $logHandle = false;
    if(!($logHandle = fopen($logFile, 'a'))){
        error_log("Failed to open log file: " . $logFile);
        fclose($logHandle);
        return;
    }

    if(!flock($logHandle, LOCK_EX)){
        error_log("Failed to lock log file: " . $logFile);
        return;
    }

    fwrite($logHandle, $message);
    flock($logHandle, LOCK_UN);
    fclose($logHandle);
}

/**
 * Add an information log line.
 */
function logInfo(string $message, ?string $file = null, ?int $line = null){
    logMessage('Info', $message, $file, $line);
}

/**
 * Add an error log line.
 */
function logError(string $message, ?string $file = null, ?int $line = null){
    logMessage('Error', $message, $file, $line);
}