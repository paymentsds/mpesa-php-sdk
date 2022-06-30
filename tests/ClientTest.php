<?php
namespace Paymentsds\MPesa\Tests;

use Paymentsds\MPesa\Client;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class ClientTest extends TestCase
{
    public function setup():void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->client = new Client([
            'apiKey' => $_ENV['API_KEY'],                               // API Key
            'publicKey' => $_ENV['PUBLIC_KEY'],                         // Public Key
            'serviceProviderCode' => $_ENV['SERVICE_PROVIDER_CODE']     // input_ServiceProviderCode
         ]);

        $this->clientRevert = new Client([
            'apiKey' => $_ENV['API_KEY'],                               // API Key
            'publicKey' => $_ENV['PUBLIC_KEY'],                         // Public Key
            'serviceProviderCode' => $_ENV['SERVICE_PROVIDER_CODE'],    // input_ServiceProviderCode
            'initiatorIdentifier' => $_ENV['INITIATOR_IDENTIFIER'],     // input_InitiatorIdentifier,
            'securityCredential' => $_ENV['SECURITY_CREDENTIAL']        // input_SecurityCredential
         ]);
         
        $this->paymentDataSend = [
            'to' => $_ENV['PHONE_NUMBER'],                              // input_CustomerMSISDN
            'reference' => '11115' . rand(1, 99),                       // input_ThirdPartyReference
            'transaction' => 'T12344CC',                                // input_TransactionReference
            'amount' => '10'                                            // input_Amount
         ];

        $this->paymentDataReceive = [
            'from' => $_ENV['PHONE_NUMBER'],                                   // input_CustomerMSISDN
            'reference' => '11114'  . rand(1, 99),                      // input_ThirdPartyReference
            'transaction' => 'T12344CC',                                // input_TransactionReference
            'amount' => '10'                                            // input_Amount
         ];

        $this->paymentDataBusiness = [
            'to' => '979797',                                           // input_ReceiverPartyCode
            'reference' => '11114'   . rand(1, 99),                     // input_ThirdPartyReference
            'transaction' => 'T12344CC',                                // input_TransactionReference
            'amount' => '10'                                            // input_Amount
         ];

        $this->paymentDataRevert = [
            'reference' => '11114' . rand(1, 99),                       // input_ThirdPartyReference
            'transaction' => 'T12344CC',                                // input_TransactionReference
            'amount' => '10'                                            // input_Amount
         ];
    }
    public function testSend()
    {
        $result = $this->client->send($this->paymentDataSend);
        $this->assertTrue($result->success);
    }

    public function testSendBusiness()
    {
        $result = $this->client->send($this->paymentDataBusiness);
        $this->assertTrue($result->success);
    }

    public function testReceive()
    {
        $result = $this->client->receive($this->paymentDataReceive);
        $this->assertTrue($result->success);
    }

    public function testRevert()
    {
        $result = $this->clientRevert->revert($this->paymentDataRevert);
        $this->assertTrue($result->success);
    }
}
