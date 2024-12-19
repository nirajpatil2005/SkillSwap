<?php
// error_handler.php

// Custom error handling function
function customError($errno, $errstr, $errfile, $errline) {
    $logMessage = "[Error $errno] $errstr - Error on line $errline in file $errfile\n";
    
    // Log error to a file
    error_log($logMessage, 3, 'errors.log'); // Log to 'errors.log' file
    
    // Optional: Display error details to the user (you can comment this out in production)
    echo "<script>alert('An error occurred. Please check the error logs for details.');</script>";
}

// Custom exception handler
function customException($exception) {
    $logMessage = "[Exception] " . $exception->getMessage() . " - Exception in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";

    // Log exception to a file
    error_log($logMessage, 3, 'errors.log'); // Log to 'errors.log' file
    
    // Optional: Display exception details to the user
    echo "<script>alert('An exception occurred. Please check the error logs for details.');</script>";
}

// Set custom error and exception handlers
set_error_handler("customError");
set_exception_handler("customException");
?>
