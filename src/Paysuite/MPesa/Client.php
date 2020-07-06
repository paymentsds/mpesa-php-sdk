<?php
namespace Paysuite\MPesa;

class Client {
	private $service;

	public function __construct($args)
	{
		$this->service = new Service($args);
	}

	public function send($intent) {
		$this->service->handleSend($intent);
	}

	public function receive($intent) {
		$this->service->handleReceive($intent);
	}
	
	public function revert($intent) {
		$this->service->handleRevert($inent);
	}
	
	public function query($intent) {
		$this->service->handleQuery($intent);
	}
}
