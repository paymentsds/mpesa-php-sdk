<?php
namespace Paymentsds\MPesa;

class Constants
{
    const C2B_PAYMENT = 'C2B_PAYMENT';
    const B2B_PAYMENT = 'B2B_PAYMENT';
    const B2C_PAYMENT = 'B2C_PAYMENT';
    const REVERSAL = 'REVERSAL';
    const QUERY_TRANSACTION_STATUS = 'QUERY_TRANSACTION_STATUS';

    const C2B_PAYMENT_PORT = '18352';
    const B2B_PAYMENT_PORT = '18349';
    const B2C_PAYMENT_PORT = '18345';
    const REVERSAL_PORT = '18354';
    const QUERY_TRANSACTION_STATUS_PORT = '18353';

    const POST = 'post';
    const GET = 'get';
    const PUT = 'put';

    const OPERATIONS = [
        self::C2B_PAYMENT => [
            'method' => self::POST,
            'port'   => self::C2B_PAYMENT_PORT,
            'path'   => '/ipg/v1x/c2bPayment/singleStage/',
            'mapping' => [
                'to' => 'input_ServiceProviderCode',
                'from' => 'input_CustomerMSISDN',
                'amount' => 'input_Amount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionReference',

            ],
            'validation' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'from' => self::PATTERNS['PHONE_NUMBER'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
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
                'to' => 'input_ReceiverPartyCode',
                'from' => 'input_PrimaryPartyCode',
                'amount' => 'input_Amount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionReference',
            ],
            'validation' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'from' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
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
            'method' => self::PUT,
            'port'   => self::REVERSAL_PORT,
            'path'   => '/ipg/v1x/reversal/',
            'mapping' => [
                'to' => 'input_ServiceProviderCode',
                'amount' => 'input_ReversalAmount',
                'reference' => 'input_ThirdPartyReference',
                'transaction' => 'input_TransactionID',
                'securityCredential' => 'input_SecurityCredential',
                'initiatorIdentifier' => 'input_InitiatorIdentifier'
            ],
            'validation' => [
                'to' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'amount' => self::PATTERNS['MONEY_AMOUNT'],
                'reference' => self::PATTERNS['WORD'],
                'transaction' => self::PATTERNS['WORD'],
                'securityCredential' => self::PATTERNS['WORD'],
                'initiatorIdentifier' => self::PATTERNS['WORD']
            ],
            'required' => [
                'to',
                'amount',
                'reference',
                'transaction',
                'securityCredential',
                'initiatorIdentifier'
            ],
            'optional' => [
                'to',
                'securityCredential',
                'initiatorIdentifier'
            ]
        ],

        self::QUERY_TRANSACTION_STATUS => [
            'method' => self::GET,
            'port'   => self::QUERY_TRANSACTION_STATUS_PORT,
            'path'   => '/ipg/v1x/queryTransactionStatus/',
            'mapping' => [
                'from' => 'input_ServiceProviderCode',
                'subject' => 'input_QueryReference',
                'reference' => 'input_ThirdPartyReference'
            ],
            'validation' => [
                'from' => self::PATTERNS['SERVICE_PROVIDER_CODE'],
                'subject' => self::PATTERNS['WORD'],
                'reference' => self::PATTERNS['WORD'],
            ],
            'required' => [
                'from',
                'subject',
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
