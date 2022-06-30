<?php

namespace Paymentsds\MPesa;

class Response
{
    const PARAMS = [
        'success',
        'error',
        'data'
    ];

    private $success;
    private $error;
    private $data;

    public function __construct($success, $error, $data = null)
    {
        $this->success = $success;
        $this->error= $error;
        $this->data = $data;
    }

    public function __get($property)
    {
        if (in_array($property, self::PARAMS)) {
            return $this->{$property};
        }
        
        return null;
    }
}
