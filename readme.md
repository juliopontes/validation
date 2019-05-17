## Validation Framework

A Simple Validation for your data.

### Features

- API like Laravel Validation
- Array Validation
- Custom attribute aliases
- Custom validation messages
- Custom Rule

### Quick Start

##### Usage

```php
use Validation\Validator;

// data to validate
$data = [
    'name' => '',
    'email' => ''
];
// rules schema to validate
$rules = [
    'name' => 'required|min:4',
    'email' => 'required|email'
];

$validation = Validator::make(array $data, array $rules);

if ($validation->fails()) {
    $response = [
        'errors' => $validation->errors()
    ];
} else {
    $response = $validation->getValidData();
}

echo '<pre>';
print_r($response);
```

