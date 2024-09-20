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

    $message = $type . " -- " . $message . ($extraData != ''? " -- ($extraData)": '') . "\r\n";
    $logHandle = fopen($logFile, 'a');
    fwrite($logHandle, $message);
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