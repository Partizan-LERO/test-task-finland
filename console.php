<?php

require __DIR__ . './vendor/autoload.php';

use App\Console\Handler;


$handler = new Handler();

if (array_key_exists(1, $argv)) {
    try {
        $handler->run($argv[1]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    $handler->list();
}

