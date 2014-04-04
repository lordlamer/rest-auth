<?php

/**
 *
 */
namespace Auth;

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

		// ldap auth
		$app->get('/auth/ldap/:username/:password', function ($username, $password) {
			// load ldap adapter
			$auth = new \Auth\Ldap($this->configFile);

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

		$app->run();
	}
}