<?php 
return [

    'paths' => ['api/*', 'movies', 'movies/*'], // ✅ allow movies routes too

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000','http://localhost:5174' ], // ✅ Nuxt dev server

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

