<?php
namespace Paymentsds\MPesa;

use Paymentsds\MPesa\Configuration;

class Service
{
    private $httpClient;
    private $config;

    public function __construct($args)
    {
        $this->config = new Configuration($args);
    }

    public function handleSend($intent)
    {
        $opcode = $this->detectOperation($intent);

        if ($opcode == null) {
            // Handle error
        }

        return $this->handleRequest($opcode, $intent);
    }

    public function handleReceive($intent)
    {
        return handleRequest(Constants::C2B_PAYMENT, $intent);
    }

    public function handleQuery($inent)
    {
        return handleRequest(Constants::QUERY_TRANSACTION_STATUS, $intent);
    }

    public function handleRevert($intent)
    {
        return handleRequest(Constants::REVERSAL, $intent);
    }

    public function handleRequest($opcode, $intent)
    {
        $data = $this->fillOptionalProperties($opcode, $intent);
        
        $missingProperties = $this->detectMissingProperties($opcode, $data);
        if (count($missingProperties) > 0) {
            throw new \Exception('Missing properties');
        }

        $validationErrors = $this->detectErrors($opcode, $data);
        if (count($validationErrors) > 0) {
            throw new \Exception('Validation errors');
        }

        return $this->performRequest($opcode, $data);
    }

    private function detectOperation($intent)
    {
        if (isset($intent['to'])) {
            if (preg_match(Constants::PATTERNS['PHONE_NUMBER'], $intent['to'])) {
                return Constants::B2C_PAYMENT;
            }
            
            if (preg_match(Constants::PATTERNS['SERVICE_PROVIDER_CODE'], $intent['to'])) {
                return Constants::B2B_PAYMENT;
            }
        }

        throw new \Exception('Invalid operation');
    }

    private function detectMissingProperties($opcode, $intent)
    {
        $operation = Constants::OPERATIONS[$opcode];
        
        $requires = $operation['required'];
        $missing = array_filter($requires, function ($v, $k) use ($intent) {
            return !(isset($intent[$v]));
        }, ARRAY_FILTER_USE_BOTH);
        
        return $missing;
    }

    private function detectErrors($opcode, $intent)
    {
        $operation = Constants::OPERATIONS[$opcode];
                
        $requires = $operation['required'];
        $errors = array_filter($requires, function ($v, $k) use ($intent, $operation) {
            return !preg_match($operation['validation'][$v], $intent[$v]);
        }, ARRAY_FILTER_USE_BOTH);
        
        return $errors;
    }

    private function fillOptionalProperties($opcode, $intent)
    {
        switch ($opcode) {
            case Constants::C2B_PAYMENT:
            case Constants::B2B_PAYMENT:
                foreach (['to' => 'serviceProviderCode'] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;
                
            case Constants::B2C_PAYMENT:
                foreach (['from' => 'serviceProviderCode'] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;

            case Constants::REVERSAL:
                foreach ([
                    'initiatorIdentifier' => 'initiatorIdentifier',
                     'securityCredential'  => 'securityCredential'
                ] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;

            case Constants::QUERY_TRANSACTION_STATUS:
                foreach (['to' => 'serviceProviderCode'] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;
        }

        return $intent;
    }

    private function buildRequestBody($opcode, $intent)
    {
        $operation = Constants::OPERATIONS[$opcode];
        $body = [];
        
        foreach ($intent as $oldKey => $value) {
            $newKey = $operation['mapping'][$oldKey];
            $body[$newKey] = $value;
        }

        return $body;
    }

    private function buildRequestHeaders($opcode, $indent)
    {
        $headers = [
            'User-Agent' => $this->config->userAgent,
            'Origin' => $this->config->origin,
            'Content-Type' => "application/json",
            'Authorization' => sprintf("Bearer %s", $this->config->auth)
        ];
        
        return $headers;
    }

    private function performRequest($opcode, $intent)
    {
        $this->generateAccessToken();
        
        if (isset($this->config->environment)) {
            if (isset($this->config->auth)) {
                $operation = Constants::OPERATIONS[$opcode];
                $headers = $this->buildRequestHeaders($opcode, $intent);
                $body = $this->buildRequestBody($opcode, $intent);
                                
                $baseURL = sprintf("%s://%s:%s", $this->config->environment->scheme, $this->config->environment->domain, $operation['port']);
                
                $data = [
                    'headers' => $headers,
                    'debug' => true
                ];
                
                if ($operation['method'] == 'post') {
                    $data['json'] = $body;
                } else {
                    $data['query'] = $body;
                }
                
                $httpClient = new \GuzzleHttp\Client([
                    'base_uri' => $baseURL,
                    'timeout'  => $this->config->timeout,
                ]);
                
                $result = $httpClient->request(strtoupper($operation['method']), $operation['path'], $data);
                
                return $result;
            } else {
                throw new \Exception('No auth data');
            }
        } else {
            throw new \Exception('Invalid connection settings');
        }
    }

    private function generateAccessToken()
    {
        $this->config->generateAccessToken();
    }

    private function buildResponse($response) {
        $
        $data = json_encode($response->getBody()->getContents());

    }
}
