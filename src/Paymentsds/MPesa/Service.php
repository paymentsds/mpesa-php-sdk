<?php
namespace Paymentsds\MPesa;

use GuzzleHttp\Client;
use Paymentsds\MPesa\Response;

use Paymentsds\MPesa\ErrorType;
use Paymentsds\MPesa\Configuration;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Paymentsds\MPesa\Exception\TimeoutException;
use Paymentsds\MPesa\Exception\ValidationException;
use Paymentsds\MPesa\Exception\InvalidHostException;
use Paymentsds\MPesa\Exception\AuthenticationException;
use Paymentsds\MPesa\Exception\InvalidReceiverException;
use Paymentsds\MPesa\Exception\MissingPropertiesException;

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
        try {
            $opcode = $this->detectOperation($intent);

            return $this->handleRequest($opcode, $intent);
        } catch (InvalidReceiverException $e) {
            return new Response(false, ErrorType::INVALID_RECEIVER);
        }
    }

    public function handleReceive($intent)
    {
        return $this->handleRequest(Constants::C2B_PAYMENT, $intent);
    }

    public function handleQuery($intent)
    {
        return $this->handleRequest(Constants::QUERY_TRANSACTION_STATUS, $intent);
    }

    public function handleRevert($intent)
    {
        return $this->handleRequest(Constants::REVERSAL, $intent);
    }

    public function handleRequest($opcode, $intent)
    {
        $data = $this->fillOptionalProperties($opcode, $intent);

        $missingProperties = $this->detectMissingProperties($opcode, $data);
        if (count($missingProperties) > 0) {
            throw new MissingPropertiesException('Missing properties');
        }

        $validationErrors = $this->detectErrors($opcode, $data);
        if (count($validationErrors) > 0) {
            throw new ValidationException('Validation errors');
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

        throw new InvalidReceiverException('Invalid operation');
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
                foreach (['to' => 'serviceProviderCode'] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;
                
            case Constants::B2C_PAYMENT:
            case Constants::B2B_PAYMENT:
                foreach (['from' => 'serviceProviderCode'] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;

            case Constants::REVERSAL:
                foreach ([
                    'to' => 'serviceProviderCode',
                    'initiatorIdentifier' => 'initiatorIdentifier',
                    'securityCredential'  => 'securityCredential'
                ] as $k => $v) {
                    if (!isset($intent[$k]) && isset($this->config->{$v})) {
                        $intent[$k] = $this->config->{$v};
                    }
                }
                break;

            case Constants::QUERY_TRANSACTION_STATUS:
                foreach (['from' => 'serviceProviderCode'] as $k => $v) {
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
                    'debug' => $this->config->debugging,
                    'verify' => $this->config->verifySSL
                ];
                
                if ($operation['method'] == 'get') {
                    $data['query'] = $body;
                } else {
                    $data['json'] = $body;
                }
                
                $httpClient = new Client([
                    'base_uri' => $baseURL,
                    'timeout'  => $this->config->timeout,
                ]);
                
                
                try {
                    $response = $httpClient->request(strtoupper($operation['method']), $operation['path'], $data);
                    $body = json_decode($response->getBody()->getContents(), true);
                    return new Response(true, null, $this->buildResponse($body));
                } catch (ConnectionException $e) {
                } catch (ClientException | ServerException $e) {
                    $response = $e->getResponse();
                    $body = json_decode($response->getBody()->getContents(), true);
                    if (isset($body['output_error'])) {
                        $errorType = ErrorType::UNAUTHORIZED_API_OR_SESSION;
                    } elseif (isset($body['output_ResponseCode'])) {
                        $errorType = $this->detectErrorType($body['output_ResponseCode']);
                    } else {
                        $errorType = ErrorType::UNKNOWN;
                    }

                    return new Response(false, $errorType, $this->buildResponse($body));
                } catch (\Exception $e) {
                    return new Response(false, null, null);
                }
            } else {
                throw new AuthenticationException('No auth data');
            }
        } else {
            throw new InvalidHostException('Invalid connection settings');
        }
    }

    private function generateAccessToken()
    {
        $this->config->generateAccessToken();
    }

    private function buildResponse($body)
    {
        $mapping = [
            'output_ConversationID' => 'conversation',
            'output_ResponseCode' => 'code',
            'output_ResponseDesc' => 'description',
            'output_ThirdPartyReference' => 'reference',
            'output_ResponseTransactionStatus' => 'status',
            'output_TransactionID' => 'transaction'
        ];

        $output = [];
        foreach ($mapping as $k => $v) {
            if (in_array($k, array_keys($body))) {
                $output[$v] = $body[$k];
            }
        }

        return $output;
    }

    private function detectErrorType($code)
    {
        $mapping = [
            'INS-0'     => ErrorType::SUCCESS,
            'INS-1'     => ErrorType::INTERNAL_ERROR,
            'INS-5'     => ErrorType::TRANSACTION_CANCELLED_BY_CUSTOMER,
            'INS-6'     => ErrorType::TRANSACTION_FAILED,
            'INS-9'     => ErrorType::REQUEST_TIMEOUT,
            'INS-10'    => ErrorType::DUPLICATE_TRANSACTION,
            'INS-13'    => ErrorType::INVALID_SHORT_CODE_USED,
            'INS-14'    => ErrorType::INVALID_REFERENCE_USED,
            'INS-15'    => ErrorType::INVALID_AMOUNT_USED,
            'INS-16'    => ErrorType::SERVER_UNDER_OVERLOAD,
            'INS-17'    => ErrorType::INVALID_TRANSACTION_REFERENCE,
            'INS-18'    => ErrorType::INVALID_TRANSACTION,
            'INS-19'    => ErrorType::INVALID_REFERENCE,
            'INS-20'    => ErrorType::MISSING_PROPERTIES,
            'INS-21'    => ErrorType::INVALID_PARAMETERS,
            'INS-22'    => ErrorType::INVALID_OPERATION_TYPE,
            'INS-23'    => ErrorType::UNKOWN_STATUS,
            'INS-24'    => ErrorType::INVALID_INITIATOR_IDENTIFIER,
            'INS-25'    => ErrorType::INVALID_SECURITY_CREDENTIAL,
            'INS-993'   => ErrorType::DIRECT_DEBIT_MISSING,
            'INS-994'   => ErrorType::DIRECT_DEBIT_ALREAD_EXISTS,
            'INS-995'   => ErrorType::CUSTOMER_HAS_PROBLEMS,
            'INS-996'   => ErrorType::CUSTOMER_ACCOUNT_NOT_ACTIVE,
            'INS-997'   => ErrorType::UNKNOWN,
            'INS-998'   => ErrorType::INVALID_MARKET,
            'INS-2001'  => ErrorType::INITIATOR_AUTH_ERROR,
            'INS-2002'  => ErrorType::RECEIVER_INVALID,
            'INS-2006'  => ErrorType::INSUFICIENT_BALANCE,
            'INS-2051'  => ErrorType::INVALID_MSISDN,
            'INS-2057'  => ErrorType::LANGUAGE_CODE_INVALID,
        ];

        return $mapping[$code];
    }
}
