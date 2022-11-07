<?php
namespace Paymentsds\MPesa;

class Environment
{
    const PARAMS = [
        'scheme',
        'domain'
    ];
    
    const SANDBOX    = 'https://api.sandbox.vm.co.mz';
    const PRODUCTION = 'https://api.vm.co.mz';
    
    private $scheme;
    private $domain;
    
    public function __construct($scheme, $domain)
    {
        $this->scheme = $scheme;
        $this->domain = $domain;
    }
    
    public function __get($property)
    {
        if (in_array($property, self::PARAMS)) {
            return $this->{$property};
        }
        
        return null;
    }
    
    public static function fromURL($url)
    {
        $parts = explode("://", $url);
        return new Environment($parts[0], $parts[1]);
    }
}
