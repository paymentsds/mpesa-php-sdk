<?php
namespace Paysuite\MPesa;

class Operation {
	const PARAMS = [
		'method',
		'port',
		'path',
		'mapping',
		'validation',
		'required',
		'optional'
	];

	public function __construct($args)
	{
		for(self::PARAMS as $param) {
			if (in_array($args, $param)) {
				$this->{$param} = $args[$param];
			}
		}
	}
}
