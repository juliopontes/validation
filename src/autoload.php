<?php
spl_autoload_register(function($class_name) {
    $namespace_separator = '\\';
    $file = __DIR__ . '/' . str_replace($namespace_separator, DIRECTORY_SEPARATOR, ltrim($class_name,$namespace_separator)) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }

});