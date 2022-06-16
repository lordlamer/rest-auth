<?php

/**
 *
 */
namespace Auth;

use Laminas\Config\Reader\Ini as ConfigReader;
use Laminas\Config\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

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
        $app = AppFactory::create();

		$configReader = new ConfigReader();
		$configData = $configReader->fromFile($this->configFile);
		$config = new Config($configData, true);

        // set base path if required
        if(isset($config['general']['base_path']) && $config['general']['base_path'] != '')
            $app->setBasePath($config['general']['base_path']);

		// iterate over each config section
		foreach($config as $key => $value) {
			// check for ldap adapter
			if(isset($value['adapter']) && $value['adapter'] == 'ldap') {
				// ldap auth via GET request and username and password in url
                $app->get($value['route'].'/{username}/{password}', function (Request $request, Response $response, array $args) use ($config, $value) {
                    // load ldap adapter
                    $auth = new \Auth\Ldap($config[$value['config']]);

                    // default result
                    $res = array(
                        'status' => 'ERROR',
                        'message' => ''
                    );

                    // check for valid credentials
                    if($auth->verify($args['username'], $args['password'])) {
                        $res = array(
                            'status' => 'SUCCESS'
                        );
                    } else {
                        $res['message'] = $auth->getMessage();
                    }

                    // show json result
                    header('Content-type: application/json');
                    $response->getBody()->write(json_encode($res));
                    return $response;
                });

				// ldap auth via GET and POST request
                $app->map(['GET', 'POST'], $value['route'], function (Request $request, Response $response, array $args) use ($app, $config, $value) {
                    // load ldap adapter
                    $auth = new \Auth\Ldap($config[$value['config']]);

                    // default result
                    $res = array(
                        'status' => 'ERROR',
                        'message' => ''
                    );

                    // get username and password
                    $data = $request->getParsedBody();
                    $username = $data['username'];
                    $password = $data['password'];

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
                    $response->getBody()->write(json_encode($res));
                    return $response;
                });
			}
		}

		$app->run();
	}
}