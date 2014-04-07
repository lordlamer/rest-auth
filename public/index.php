<?php

/**
 * REST Auth
 *
 * @author Frank Habermann <lordlamer@lordlamer.de>
 * @date 20140402
 * @license BSD
 */

// use composer autoload
require '../vendor/autoload.php';

// init auth app
$app = new \Auth\Application('../config/app.ini');

// run auth app
$app->run();
