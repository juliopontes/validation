<?php
require_once dirname(__DIR__) . '/src/autoload.php';

use Validation\Validator;

// simple validation :)
$validation = Validator::make($_REQUEST, [
    'email' => 'required|string|email',
    'password' => 'required|min:6',
    'confirm_password' => 'required|same:password'
]);

if ( $validation->fails() ) {
    $response = [
        'errors' => $validation->errors()
    ];
} else {
    $response = $validation->getValidData();
}

header("Content-type: application/json; charset=utf-8;");
echo json_encode($response);