<?php

return [
    'cache' => [
        'driver' => 'file',
        'path' => 'processed_data.json',
    ],
    'db' => [
        'driver' => 'pdo_sqlite',
        'path' => 'analytical_storage.sqlite',
    ]
];
