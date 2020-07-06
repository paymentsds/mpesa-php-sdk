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
	}

	private function detectMissingProperties($opcode, $intent) {
	}

	private function detectErrors($opcode, $intent) {
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
