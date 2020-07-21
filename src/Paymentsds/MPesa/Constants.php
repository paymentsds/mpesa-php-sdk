<?php
namespace Paymentsds\MPesa;

class Constants
{
    const C2B_PAYMENT = 'C2B_PAYMENT';
    const B2B_PAYMENT = 'B2B_PAYMENT';
    const B2C_PAYMENT = 'B2C_PAYMENT';
    const REVERSAL = 'REVERSAL';
    const QUERY_TRANSACTION_STATUS = 'QUERY_TRANSACTION_STATUS';

    const C2B_PAYMENT_PORT = '18345';
    const B2B_PAYMENT_PORT = '18349';
    const B2C_PAYMENT_PORT = '18345';
    const REVERSAL_PORT = '18354';
    const QUERY_TRANSACTION_STATUS_PORT = '18353';

    const POST = 'post';
    const GET = 'get';

    const OPERATIONS = [
        self::C2B_PAYMENT => [
            'method' => self::POST,
            'port'   => self::C2B_PAYMENT_PORT,
            'path'   => '/ipg/v1x/b2cPayment/',
            'mapping' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'from' => self::PATTERNS['PHONE_NUMBER'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
            ],
            'validation' => [
                'to' => 'input_ServiceProviderCode',
                'from' => 'input_CustomerMSISDN',
                'amount' => 'input_Amount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionReference',
            ],
            'required' => [
                'from',
                'reference',
                'transaction',
                'amount'
            ],
            'optional' => [
                'to'
            ]
        ],

        self::B2B_PAYMENT => [
            'method' => self::POST,
            'port'   => self::B2B_PAYMENT_PORT,
            'path'   => '/ipg/v1x/b2bPayment/',
            'mapping' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'from' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
            ],
            'validation' => [
                'to' => 'input_ReceiverPartyCode',
                'from' => 'input_PrimaryPartyCode',
                'amount' => 'input_Amount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionReference',
            ],
            'required' => [
                'to',
                'amount',
                'reference',
                'transaction'
            ],
            'optional' => [
                'from'
            ]

        ],

        self::B2C_PAYMENT => [
            'method' => self::POST,
            'port'   => self::B2C_PAYMENT_PORT,
            'path'   => '/ipg/v1x/b2cPayment/',
            'mapping' => [
                'to' => 'input_CustomerMSISDN',
                'from' => 'input_ServiceProviderCode',
                'amount' => 'input_Amount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionReference',
            ],
            'validation' => [
                'to' => self::PATTERNS['PHONE_NUMBER'],
                'from' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
            ],
            'required' => [
                'to',
                'from',
                'amount',
                'reference',
                'transaction'
            ],
            'optional' => [
                'from'
            ]
        ],

        self::REVERSAL => [
            'method' => self::POST,
            'port'   => self::REVERSAL_PORT,
            'path'   => '/ipg/v1x/reversal/',
            'mapping' => [
                'to' => 'input_ServiceProviderCode',
                'amount' => 'input_ReversalAmount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionID',
                'security_credential' => 'security_credential',
                'initiator_identifier' => 'initiator_identifier'
            ],
            'validation' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
                'security_credential' => self::PATTERNS['WORD'],
                'initiator_identifier' => self::PATTERNS['WORD']
            ],
            'required' => [
                'to',
                'amount',
                'reference',
                'transaction',
                'security_credential',
                'initiator_identifier'
            ],
            'optional' => [
                'to',
                'security_credential',
                'initiator_identifier'
            ]
        ],

        self::QUERY_TRANSACTION_STATUS => [
            'method' => self::GET,
            'port'   => self::QUERY_TRANSACTION_STATUS_PORT,
            'path'   => '/ipg/v1x/queryTransactionStatus/',
            'mapping' => [
                'from' => 'input_ServiceProviderCode',
                'query' => 'input_QueryReference',
                'reference' => 'input_ThirdPartyReference'
            ],
            'validation' => [
                'from' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
            ],
            'required' => [
                'from',
                'amount',
                'reference'
            ],
            'optional' => [
                'from'
            ]
        ]
    ];

    const PATTERNS = [
        'PHONE_NUMBER' => '/^((00|\+)?258)?8[45][0-9]{7}$/',
        'WORD' => '/^\w+$/',
        'MONEY_AMOUNT' => '/^[1-9][0-9]*(\.[0-9]+)?$/',
        'SERVICE_PROVIDER_CODE' => '/^[0-9]{5,6}$/'
    ];
}
