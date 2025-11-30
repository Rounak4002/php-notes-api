<?php
// config.php
// Copy this file to config_local.php or set proper values here.
// Do NOT commit real credentials to public repos.

return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'notes_api',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    // base url used for docs or generation (not required for API)
    'base_url' => 'http://localhost:8000'
];
