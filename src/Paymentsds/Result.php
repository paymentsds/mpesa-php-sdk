<?php

namespace Paymentsds\MPesa;

class Result {
    const PARAMS = [
        'success',
        'code',
        'description',
        'headers',
        'data'
    ];

    private $success;
    private $code;
    private $description;
    private $data;

    public function __construct($success, $code, $headers, $description, $data) {
        $this->success = $success;
        $this->code= $code;
        $this->status = $status;
        $this->headers = $headers;
        $this->description = $description;
        $this->data = $data;
    }

    public function __get($property) {
        if (in_array($property, self::PARAMS)) {
            return $this->{$property};
        }
        
        return null;
    }

}