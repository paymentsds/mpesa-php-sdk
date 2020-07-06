<?php
namespace Paysuite\MPesa;
class Configuration {
	public const PARAMS = [
		'apiKey',
		'publicKey',
		'accessToken',
		'timeout',
		'debugging',
		'verifySSL',
		'origin',
		'initiatorIdentifier',
		'securityCredential',
		'serviceProviderCode',
		'userAgent', 
		'environment'
	];

	public function __construct(array $args) {
		$this->environment = 'sandbox';
		$this->debugging = true;
		$this->verifySSL = false;
		$this->origin = '*';
		$this->userAgent = 'MPesa-PHP';

		foreach (self::PARAMS as $param) {
			if (!empty($args[$param])) {
				$this->{$param} = $args[$param];
			}
		}
	}

	public function __set($property, $value) {
		if (in_array($property, self::PARAMS)) {
			$this->{$property} = $value;
		}
	}

	public function __get($property) {
		return $this->{$property};
	}
}
