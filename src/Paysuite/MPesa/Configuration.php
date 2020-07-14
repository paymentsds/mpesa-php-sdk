<?php
namespace Paysuite\MPesa;

class Configuration
{
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
        'environment',
        'auth'
    ];

    public function __construct(array $args)
    {
        $this->environment = Environment::fromURL(Environment::SANDBOX);
        $this->debugging = true;
        $this->verifySSL = false;
        $this->origin = '*';
        $this->userAgent = 'MPesa-PHP';
        $this->timeout = 0;

        foreach (self::PARAMS as $param) {
            if (!empty($args[$param])) {
                if ($param === 'environment') {
                    if ($args['environment'] == Environment::PRODUCTION) {
                        $this->environment = Environment::fromURL(Environment::PRODUCTION);
                    } else {
                        $this->environment = Environment::fromURL(Environment::SANDBOX);
                    }
                } else {
                    $this->{$param} = $args[$param];
                }
            }
        }
    }

    public function __set($property, $value)
    {
        if (in_array($property, self::PARAMS)) {
            $this->{$property} = $value;
        }
    }

    public function __get($property)
    {
        return $this->{$property};
    }

    public function generateAccessToken()
    {
        $hasKeys = isset($this->apiKey) && isset($this->publicKey);
        $hasAccessToken = isset($this->accessToken);

        if ($hasKeys) {
            $publicKey = openssl_get_publickey($this->formatPublicKey());

            openssl_public_encrypt($this->apiKey, $accessToken, $publicKey, OPENSSL_PKCS1_PADDING);
            
            $this->auth = base64_encode($accessToken);
        }

        if ($hasAccessToken) {
            $this->auth = $this->accessToken;
        }
    }

    private function formatPublicKey()
    {
		$publicKeyHeader = "-----BEGIN PUBLIC KEY-----\n";
		$publicKeyFooter = "\n-----END PUBLIC KEY-----";

		return sprintf("%s%s%s", $publicKeyHeader, $this->publicKey, $publicKeyFooter);
    }
}
