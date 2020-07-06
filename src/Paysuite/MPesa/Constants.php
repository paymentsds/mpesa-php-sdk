<?php
namespace Paysuite\MPesa;

class Constants {

	const C2B_PAYMENT = 'C2B_PAYMENT';
	const B2B_PAYMENT = 'B2B_PAYMENT';
	const B2C_PAYMENT = 'B2C_PAYMENT';
	const REVERSAL = 'REVERSAL';
	const QUERY_TRANSACTION_STATUS = 'QUERY_TRANSACTION_STATUS';

	const PHONE_NUMBER = '';
	const WORD = '';
	const SERVICE_PROVIDER_CODE = '';
	const MONEY_AMOUNT = '';

	const HTTP = [
		'GET'  => 'get',
		'POST' => 'post'
	];

	const C2B_PAYMENT_PORT = '';
	const B2B_PAYMENT_PORT = '';
	const B2C_PAYMENT_PORT = '';
	const REVERSAL_PORT = '';
	const QUERY_TRANSACTION_STATUS_PORT = '';


	public static $operations = [
		self::C2B_PAYMENT => new Operation([
			'method' => '',
			'port'   => '',
			'path'   => '',
			'mapping' => [
			],
			'validation' => [
			],
			'required' => [
			],
			'optional' => [
			],
			'type' => 'body'

		]),

		self::B2B_PAYMENT => new Operation([
		]),

		self::B2C_PAYMENT => new Operation([
		]),

		self::REVERSAL => new Operation([
		]),

		self::QUERY_TRANSACTION_STATUS => new Operation([
		])
	];

	public static $patterns = [
		self::PHONE_NUMBER => '/^((00|\+)?258)8[45][0-9]{7}?/$',
		self::WORD => '/^\w+$/',
		self::MONEY_AMOUNT => '/^[1-9][0-9]*(\.[0-9]+)?$/',
		self::SERVICE_PROVIDER_CODE => '/^[0-9]{5,6}$/'
	];
}

