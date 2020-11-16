<?php

namespace Auth;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\Ldap as AuthAdapter;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream as LogWriter;
use Laminas\Log\Filter\Priority as LogFilter;

/**
 *
 */
class Ldap {
	/**
	 *
	 */
	protected $config = null;

	/**
	 *
	 */
	protected $message = '';

	/**
	 *
	 */
	public function __construct($config) {
		$this->config = $config;
	}

	/**
	 *
	 */
	public function verify($username, $password) {
		$auth = new AuthenticationService();

		$log_path = $this->config->ldap->log_path;
		$options = $this->config->ldap->toArray();
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