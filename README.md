# PHP M-Pesa SDK


<p align="center">
<a href="https://github.com/paymentsds/mpesa-php-sdk"><img src="https://img.shields.io/packagist/dt/paymentsds/mpesa" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/paymentsds/mpesa"><img src="https://img.shields.io/github/v/release/paymentsds/mpesa-php-sdk?include_prereleases" alt="Latest Stable Version"></a>
<a href="https://opensource.org/licenses/Apache-2.0"><img src="https://img.shields.io/badge/License-Apache_2.0-blue.svg" alt="License"></a>
</p>

This is a library willing to help you to integrate the [Vodacom M-Pesa](https://developer.mpesa.vm.co.mz) operations to your application.

<br>

### Features

Using this library, you can implement the following operations:

- Receive money from a mobile account to a business account (C2B)
- Send money from a business account to a mobile account (B2C)
- Send money from a business account to another business account (B2B)
- Revert any of the transactions above mentioned
- Query the status of a transaction

<br><br>

## Requirements

- [PHP 7.2+](https://php.net/downloads)
- [Composer](https://getcomposer.org)
- Valid credentials obtained from the [Mpesa Developer](https://developer.mpesa.vm.co.mz) portal
- Port 18352 open on your server (usually open on local env)


<br><br>


## Installation

<br>

### Using Composer

```bash
composer require paymentsds/mpesa
```

<br>

### Manual Installation
```bash
git clone https://github.com/paymentsds/mpesa-php-sdk mpesa-php-sdk
cd mpesa-php-sdk
composer install
```

<br><br>


## Usage

Using this SDK is very simple and fast, let us see some examples:

<br>

#### C2B Transaction (Receive money from mobile account)

```php
use Paymentsds\MPesa\Client;

$client = new Client([
   'apiKey' => '<REPLACE>',             // API Key
   'publicKey' => '<REPLACE>',          // Public Key
   'serviceProviderCode' => '<REPLACE>' // Service Provider Code
]);

$paymentData = [
   'from' => '841234567',       // Customer MSISDN
   'reference' => '11114',      // Third Party Reference
   'transaction' => 'T12344CC', // Transaction Reference
   'amount' => '10'             // Amount
];

$result = $client->receive($paymentData);

if ($result->success) {
   // Handle success
} else {
   // Handle failure
}
```

<br>

#### B2C Transaction (Sending money to mobile account)

```php
use Paymentsds\MPesa\Client;

$client = new Client([
   'apiKey' => '<REPLACE>',             // API Key
   'publicKey' => '<REPLACE>',          // Public Key
   'serviceProviderCode' => '<REPLACE>' // Service Provider Code
]);

$paymentData = [
   'to' => '841234567',       // Customer MSISDN
   'reference' => '11114',      // Third Party Reference
   'transaction' => 'T12344CC', // Transaction Reference
   'amount' => '10'             // Amount
];

$result = $client->send($paymentData);

if ($result->success) {
   // Handle success scenario
} else {
   // Handle failure scenario
}
```

<br>

#### B2B Transaction (Sending money to business account)

```php
$client = new Client([
   'apiKey' => '<REPLACE>',             // API Key
   'publicKey' => '<REPLACE>',          // Public Key
   'serviceProviderCode' => '<REPLACE>' // Service Provider Code
]);

$paymentData = [
   'from' => '979797',       // Receiver Party Code
   'reference' => '11114',      // Third Party Reference
   'transaction' => 'T12344CC', // Transaction Reference
   'amount' => '10'             // Amount
];

$result = $client->send($paymentData)

if ($result->success) {
   // Handle success scenario
} else {
   // Handle failure scenario
}
```

<br>


#### Transaction Reversal

```php
use Paymentsds\MPesa\Client;

$client = new Client([
   'apiKey' => '<REPLACE>',             // API Key
   'publicKey' => '<REPLACE>',          // Public Key
   'serviceProviderCode' => '<REPLACE>', // Service Provider Code
   'initiatorIdentifier' => '<REPLACE>', // Initiator Identifier
   'securityIdentifier' => '<REPLACE>'  // Security Credential
]);

$paymentData = [
   'reference' => '11114',      // Third Party Reference
   'transaction' => 'T12344CC', // Transaction Reference
   'amount' => '10'             // Amount
];

$result = $client->revert($paymentData);

if ($result->success) {
   // Handle success scenario
} else {
   // Handle failure scenario
}
```

<br>

#### Query the transaction status

```php
use Paymentsds\MPesa\Client;

$client = new Client([
   'apiKey' => '<REPLACE>',             // API Key
   'publicKey' => '<REPLACE>',          // Public Key
   'serviceProviderCode' => '<REPLACE>' // Service Provider Code
]);

$paymentData = [
   'subject' => '11114',      // Query Reference
   'transaction' => 'T12344CC', // Transaction Reference
];

$result = $client->query($paymentData);

if ($result->success) {
   // Handle success scenario
} else {
   // Handle failure scenario
}
```

<br><br>

## Friends

- [M-Pesa SDK for Javascript](https://github.com/paymentsds/mpesa-js-sdk)
- [M-Pesa SDK for Python](https://github.com/paymentsds/mpesa-python-sdk)
- [M-Pesa SDK for Java](https://github.com/paymentsds/mpesa-java-sdk)
- [M-Pesa SDK for Ruby](https://github.com/paymentsds/mpesa-ruby-sdk)


<br><br>

## Authors <a name="authors"></a>

- [Anísio Mandlate](https://github.com/AnisioMandlate)
- [Edson Michaque](https://github.com/edsonmichaque)
- [Elton Laice](https://github.com/eltonlaice)
- [Nélio Macombo](https://github.com/neliomacombo)


<br><br>

## Contributing

Thank you for considering contributing to this package. If you wish to do it, email us at [developers@paymentsds.org](mailto:developers@paymentsds.org) and we will get back to you as soon as possible.


<br><br>

## Security Vulnerabilities

If you discover a security vulnerability, please email us at [developers@paymentsds.org](mailto:developers@paymentsds.org) and we will address the issue with the needed urgency.

<br><br>

## License

Copyright 2020 &copy; The PaymentsDS Team

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
