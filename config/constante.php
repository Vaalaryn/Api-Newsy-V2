<?php
return [
    'regex' => [
        'username' => '/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/',
        'password' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!#$%&?@])[0-9a-zA-Z!#$%&?@]{8,}$/'
    ],
    'type_retour' => [
        'ok' => 200,
        'bad_request' => 400,
        'abort' => 403,
        'not_found' => 404,
        'erreur' => 500,
    ],
    'value' => [
        'space' => " "
    ],
    'validation' => [
        'mail_unique' => 'required|max:100|email|unique:users',
        'mail' => 'required|max:100|email|exists:users',
        'username' => 'required|max:60|regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/',
        'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!#$%&?@])[0-9a-zA-Z!#$%&?@]{8,}$/',
        'password_confirm' => 'required|same:password',
        'token' => 'required|exist:users|max:22|min:22'
    ],
    'token' => [
        'limit' => 7,
        'length' => 22
    ]
];