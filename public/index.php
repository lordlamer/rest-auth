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

// ldap auth
$app->get('/auth/ldap/:username/:password', function ($username, $password) {
	// load ldap adapter
	$auth = new \Auth\Ldap('../config/ldap.ini');

	// default result
	$res = array(
		'status' => 'ERROR'
	);

	// check for valid credentials
	if($auth->verify($username, $password)) {
		$res = array(
			'status' => 'SUCCESS'
		);
	}

	// show json result
	header('Content-type: application/json');
	echo json_encode($res);
});

$app->run();
