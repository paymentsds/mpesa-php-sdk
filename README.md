# MPesa SDK for PHP

MPesa PHP SDK is an unofficial library to help developers integrating every [MPesa](https://developer.mpesa.vm.co.mz) operations to their PHP applications.

## Contents

1. [Features](#features)
1. [Requirements](#requirements)
1. [Configuration](#configuration)
1. [Usage](#usage)
   1. [Quickstart](#contributing)
   1. [Examples](#contributing)
1. [Installation](#installation)
   1. [Using Rubygems](#installation-composer)
   1. [Manual Installation](#installation-manual)
1. [Related Projects](#related)
   1. [Dependencies](#contributing)
   1. [Friends](#contributing)
   1. [Alternatives](#contributing)
1. [Contributing](#contributing)
1. [Changelog](#changelog)
1. [Authors](#authors)
1. [Credits](#credits)
1. [License](#license)

## Features <a name="features"></a>
- Make C2B transaction
- Make B2C transaction
- Make B2B transaction
- Revert a transaction
- Query transaction status

## Requirements <a name="requirements"></a>

- [PHP](https://php.net)
- [Composer](https://getcomposer.org)

## Usage <a name="usage"></a>
### Quickstart <a name="usage-quickstart"></a>
```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

result = client.receive(form)

if (result.isSuccess()) {
    echo json_encode(result.data);
} else {
    echo json_encode(result.data);
}
```

### Receive money from a mobile wallet

```PHP
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

### Send money to a mobile wallet

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

### Send money to a business wallet

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

### Revert a transaction

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

### Query the status of a transaction

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

### Examples <a name="usage-examples"></a>
- [Make C2B transaction](examples/c2b_payment.php)
- [Make B2C transaction](examples/b2c_payment.php)
- [Make B2B transaction](examples/b2b_payment.php)
- [Revert a transaction](examples/reversal.php)
- [Query transaction status](examples/query_transaction_status.php)

## Installation <a name="installation"></a>
### Using Rubygems <a name="installation-rubygems"></a>
```bash
$ composer require paysuite-mpesa
```

### Using Bundler <a name="installation-rubygems"></a>
```json
{
	"require": {
    	"paysuite/mpesa": "*"
	}
}
```

```bash
$ composer install
```

### Manual Installation <a name="installation-manual"></a>
```bash
$ git clone https://github.com/paysuite/mpesa-ruby-sdk.git
$ cd mpesa-js-sdk
$ composer install
```

## Configuration <a name="configuration"></a>
The complete set of configurations looks like this:

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

The minimal configuration is:
```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

Or if you have pre-calculated the access token offline:

```php
use Paysuite\MPesa\Client;

client = new Client([
    'apiKey' => '<REPLACE>',
    'publicKey' => '<REPLACE>',
    'serviceProviderCode' => '<REPLACE>',
]);

form = [
    'from' => '84XXXXXX',
    'amount' => '10',
    'transaction' => 'TX',
    'reference' => 'REF'
]

client.receive(form)
```

## Related Projects <a name="related"></a>

### Dependencies <a name="related-dependencies"></a>
- [GuzzleHTTP](https://github.com/lostisland/faraday)

### Friends <a name="related-friends"></a>
TODO: 

### Alternatives <a name="related-alternatives"></a>
TODO: 

### Inspiration
- [rosariopfernandes/mpesa-node-api](https://github.com/abdulmueid/mpesa-php-api)
- [karson/mpesa-php-sdk](https://github.com/karson/mpesa-php-sdk)
- [codeonweekends/mpesa-php-sdk](https://github.com/codeonweekends/mpesa-php-sdk)
- [abdulmueid/mpesa-php-api](https://github.com/abdulmueid/mpesa-php-api)
- [realdm/mpesasdk](https://github.com/realdm/mpesasdk)

## Contributing <a name="contributing"></a>

## Changelog <a name="changelog"></a>

## Authors <a name="authors"></a>

- [Edson Michaque](https://github.com/edsonmichaque)
- [Nélio Macombo](https://github.com/neliomacombo)

## Credits <a name="credits"></a>

## License <a name="license"></a>

Copyright 2020 Edson Michaque and Nélio Macombo

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

