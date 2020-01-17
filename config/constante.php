<?php
return [
    'regex' => [
        'username' => '/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/',
        'password' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!#$%&?@])[0-9a-zA-Z!#$%&?@]{8,}$/'
    ],
    'type_retour' => [
        'erreur' => 500,
        'ok' => 200
    ],
    'value' => [
        'space' => " "
    ],
    'validation' => [
        'mail' => 'required|max:100|email|unique:users',
        'username' => 'required|max:60|regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/|unique:users',
        'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!#$%&?@])[0-9a-zA-Z!#$%&?@]{8,}$/',
        'password_confirm' => 'required|same:password',
    ]
];