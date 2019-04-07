<?php
require_once dirname(__DIR__) . '/src/autoload.php';

use Validation\Validator;

$data = [
    'email' => 'admin@admin'
];

$validation = Validator::make($data, [
    'email' => 'required|string|email',
    'password' => 'required|min:6',
    'confirm_password' => 'required|same:password'
],[
    // global
    'required' => ':attribute is mandatory',
    // custom field required message
    'password.required' => ':attribute should be not empty'
],[
    // field => label
    'confirm_password' => 'Confirm Password'
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