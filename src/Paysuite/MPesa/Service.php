<?php
namespace Paysuite\MPesa;

class Service {
	private $httpClient;
	private $config;

	public function __construct($config) {
		$this->config= new Paysuite\MPesa::Configuration($args);	
	}

	public function handleSend($intent) {
		$opcode = $this->detectOperation($intent);
		return $this->handleRequest($opcode, $intent);
	}

	public function handleReceive($intent) {
		return handleRequest(Constants::C2B_PAYMENT, $intent);
	}

	public function handleQuery($inent) {
		return handleRequest(Constants::QUERY_TRANSACTION_STATUS, $intent);
	}

	public function handleRevert($intent) {
		return handleRequest(Constants::REVERSAL, $intent);
	}

	public function handleRequest($opcode, $inent) {
		$data = $this->fillOptionalPropertirs($opcode, $intent);

		$missingPProperties = $this->detectMissingProperties($opcode, $intent);
		if (count($missingProperties) > 0) {
			// Handle
		}

		$validationErrors = $this->detectErrors($opcode, $data);
		if (count($validationErrors) > 0) {
			// Handle
		}

		return $this->performRequest($opcode, $intent);
	}

	private function detectOperation($intent) {
		$operation = Constants::$operation[$opcode];
		
		if (isset($intent['to'])) {
			if (preg_match($operation->validation['to'], $intent['to'])) {
				return Constant::B2C_PAYMENT;
			} 
			
			if (preg_match($operation->validation['to'], $intent['to'])) {
				return Constant::B2B_PAYMENT;
			}

		}
	}

	private function detectMissingProperties($opcode, $intent) {
		$operation = Constants::$operation[$opcode];
		
		$requires = $operation->required;
		$missing = array_filter($requires, function($e) {
			return !(isset($intent[$e]));
		});
		
		return $missing;
	}

	private function detectErrors($opcode, $intent) {
		$operation = Constants::$operation[$opcode];
		
		$requires = $operation->required;
		$errors = array_filter($requires, function($e) {
			return !preg_match($operation->validation[$e], $intent[$e]);
		});
		
		return $errors;
	}

	private function fillOptionalProperties($opcode, $intent) {
	}

	private function buildRequestBody($opcode, $intent) {
	}

	private function buildRequestHeaders($opcode, $indent) {
	}

	private function performRequest($opcode, $intent) {
	}

	private function generateAccessToken() {
	}
}
