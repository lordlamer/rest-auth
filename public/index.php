<?php

/**
 * REST Auth
 *
 * @author Frank Habermann <lordlamer@lordlamer.de>
 * @date 20140402
 * @license BSD
 */

require '../vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->run();
