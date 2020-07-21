<?php
namespace Paymentsds\MPesa;

class Operation
{
    const PARAMS = [
        'method',
        'port',
        'path',
        'mapping',
        'validation',
        'required',
        'optional'
    ];

    public function __construct($args)
    {
        foreach (self::PARAMS as $param) {
            if (in_array($param, $args)) {
                $this->{$param} = $args[$param];
            }
        }
    }
    
    public function __get($property)
    {
        if (in_array($property, self::PARAMS)) {
            return $this->{$property};
        }
        
        return null;
    }
    
    public function __set($property, $value)
    {
        if (in_array($property, self::PARAMS)) {
            $this->{$property} = $value;
        }
    }
}
