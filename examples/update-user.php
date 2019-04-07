<?php
require_once dirname(__DIR__) . '/src/autoload.php';

use Validation\Validator;

$validation = Validator::make($_REQUEST, [
    'name'  => 'required',
    'email' => 'required|string|email',
    // nullable: if empty ignore field
    'password' => 'nullable|min:6',
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