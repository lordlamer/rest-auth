<?php

/**
 *
 */
namespace Auth;

use Laminas\Config\Reader\Ini as ConfigReader;
use Laminas\Config\Config;

/**
 *
 */
class Application {
	/**
	 *
	 */
	private $configFile = null;

	/**
	 *
	 */
	public function __construct($appConfig) {
		$this->configFile = $appConfig;
	}

	/**
	 *
	 */
	public function run() {
		$app = new \Slim\Slim();

		$configReader = new ConfigReader();
		$configData = $configReader->fromFile($this->configFile);
		$config = new Config($configData, true);

		// iterate over each config section
		foreach($config as $key => $value) {
			// check for ldap adapter
			if(isset($value['adapter']) && $value['adapter'] == 'ldap') {
				// ldap auth via GET request and username and password in url
				$app->get($value['route'].'/:username/:password', function ($username, $password) use ($config, $value) {
					// load ldap adapter
					$auth = new \Auth\Ldap($config[$value['config']]);

					// default result
					$res = array(
						'status' => 'ERROR',
						'message' => ''
					);

					// check for valid credentials
					if($auth->verify($username, $password)) {
						$res = array(
							'status' => 'SUCCESS'
						);
					} else {
						$res['message'] = $auth->getMessage();
					}

					// show json result
					header('Content-type: application/json');
					echo json_encode($res);
				});

				// ldap auth via GET and POST request
				$app->map($value['route'], function() use ($app, $config, $value) {
					// load ldap adapter
					$auth = new \Auth\Ldap($config[$value['config']]);

					// default result
					$res = array(
						'status' => 'ERROR',
						'message' => ''
					);

					// get username and password
					$username = $app->request->params('username');
					$password = $app->request->params('password');

					// check for valid credentials
					if($auth->verify($username, $password)) {
						$res = array(
							'status' => 'SUCCESS'
						);
					} else {
						$res['message'] = $auth->getMessage();
					}

					// show json result
					header('Content-type: application/json');
					echo json_encode($res);
				})->via('GET', 'POST');
			}
		}

		$app->run();
	}
}