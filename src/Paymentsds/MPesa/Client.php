<?php
namespace Paymentsds\MPesa;

class Client
{
    private $service;

    public function __construct($args)
    {
        return $this->service = new Service($args);
    }

    public function send($intent)
    {
        return $this->service->handleSend($intent);
    }

    public function receive($intent)
    {
        return $this->service->handleReceive($intent);
    }
    
    public function revert($intent)
    {
        return $this->service->handleRevert($intent);
    }
    
    public function query($intent)
    {
        return $this->service->handleQuery($intent);
    }
}
