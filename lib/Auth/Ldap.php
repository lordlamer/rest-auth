<?php

namespace Auth;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Config\Reader\Ini as ConfigReader;
use Zend\Config\Config;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogWriter;
use Zend\Log\Filter\Priority as LogFilter;

/**
 *
 */
class Ldap {
	/**
	 *
	 */
	protected $configFile = null;

	/**
	 *
	 */
	protected $message = '';

	/**
	 *
	 */
	public function __construct($configFile) {
		$this->configFile = $configFile;
	}

	/**
	 *
	 */
	public function verify($username, $password) {
		$auth = new AuthenticationService();

		$configReader = new ConfigReader();
		$configData = $configReader->fromFile($this->configFile);
		$config = new Config($configData, true);

		$log_path = $config->production->ldap->log_path;
		$options = $config->production->ldap->toArray();
		unset($options['log_path']);

		// init auth adapter
		$adapter = new AuthAdapter($options,
					   $username,
					   $password);

		$result = $auth->authenticate($adapter);

		// get messages
		$messages = $result->getMessages();

		// save error message if exists
		if(isset($messages[1]))
			$this->setMessage($messages[1]);

		// check for logging
		if ($log_path) {
		    $messages = $result->getMessages();

		    $logger = new Logger;
		    $writer = new LogWriter($log_path);

		    $logger->addWriter($writer);

		    $filter = new LogFilter(Logger::DEBUG);
		    $writer->addFilter($filter);

		    foreach ($messages as $i => $message) {
			if ($i-- > 1) { // $messages[2] and up are log messages
			    $message = str_replace("\n", "\n  ", $message);
			    $logger->debug("Ldap: $i: $message");
			}
		    }
		}

		// return result
		return $result->isValid();
	}

	/**
	 *
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 *
	 */
	protected function setMessage($message) {
		$this->message = $message;
	}
}