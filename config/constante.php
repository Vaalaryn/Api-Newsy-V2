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
        'token' => 'required|exists:users|max:22|min:22'
    ],
    'token' => [
        'limit' => 7,
        'length' => 22
    ],
    'newsy' => [
        'key' => env('API_KEY'),
        'url' => 'https://newsapi.org/v2/',
        'endpoints' => [
            'topHeadlines' => 'top-headlines',
            'everything' => 'everything',
            'sources' => 'sources'
        ],
        'valid_param' => [
            'topHeadlines' => [
                'restricted' => [
                    'country' => ['ae', 'ar', 'at', 'au', 'be', 'bg', 'br', 'ca', 'ch', 'cn', 'co', 'cu', 'cz', 'de', 'eg', 'fr', 'gb', 'gr', 'hk', 'hu', 'id', 'ie', 'il', 'in', 'it', 'jp', 'kr', 'lt', 'lv', 'ma', 'mx', 'my', 'ng', 'nl', 'no', 'nz', 'ph', 'pl', 'pt', 'ro', 'rs', 'ru', 'sa', 'se', 'sg', 'si', 'sk', 'th', 'tr', 'tw', 'ua', 'us', 've', 'za'],
                    'category' => ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'],
                ],
                'free' => [
                    'sources' => 'sources',//route /sources can't mix with countru or category
                    'q' => 'q', //anything
                    'pageSize' => 'pageSize', //int
                    'page' => 'page' //int
                ],
            ],
            'everything' => [

                'restricted' => [
                ],
                'free' => [
                    'q',
                    'qInTitle',
                    'sources',
                    'domains',
                    'excludeDomains',
                    'from',
                    'to',
                    'language',
                    'sortBy',
                    'pageSize',
                    'page'
                ],
            ],
            'sources' => [
                'restricted' => [
                    'country' => ['ae', 'ar', 'at', 'au', 'be', 'bg', 'br', 'ca', 'ch', 'cn', 'co', 'cu', 'cz', 'de', 'eg', 'fr', 'gb', 'gr', 'hk', 'hu', 'id', 'ie', 'il', 'in', 'it', 'jp', 'kr', 'lt', 'lv', 'ma', 'mx', 'my', 'ng', 'nl', 'no', 'nz', 'ph', 'pl', 'pt', 'ro', 'rs', 'ru', 'sa', 'se', 'sg', 'si', 'sk', 'th', 'tr', 'tw', 'ua', 'us', 've', 'za'],
                    'category' => ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'],
                    'language' => ['ar', 'de', 'en', 'es', 'fr', 'he', 'it', 'nl', 'no', 'pt', 'ru', 'se', 'ud', 'zh']
                ]
            ]
        ]
    ]
];