<?php

/**
 * REST Auth
 *
 * @author Frank Habermann <lordlamer@lordlamer.de>
 * @date 20140402
 * @license BSD
 */

require '../vendor/autoload.php';

$app = new \Auth\Application('../config/ldap.ini');
$app->run();
