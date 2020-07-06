<?php
namespace Paysuite\MPesa;

class Service {
	private $httpClient;
	private $config;

	function __construct($config) {
		$this->config= new Paysuite\MPesa::Configuration($args);	
	}

	function handleSend($intent) {
	}

	function handleReceive($intent) {
	}

	function handleQuery($inent) {
	}

	function handleRevert($intent) {
	}

	function handleRequest($opcode, $inent) {
	}

	function detectOperation($intent) {
	}

	function detectMissingProperties($opcode, $intent) {
	}

	function detectErrors($opcode, $intent) {
	}

	function fillOptionalProperties($opcode, $intent) {
	}

	function buildRequestBody($opcode, $intent) {
	}

	function buildRequestHeaders($opcode, $indent) {
	}

	function performRequest($opcode, $intent) {
	}

	function generateAccessToken() {
	}
}
