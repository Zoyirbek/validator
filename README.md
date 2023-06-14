# Validator PHP 

– Example & set new template

```
<?php

require_once "vendor/autoload.php";

use RzValidator\Validator;

$data = [
	'title' => '',
	'body' => '',
	'user_email' => 'user',
	'pw' => ['abs', '']
];

$rules = [
	'title' => [
		'required' => true,
		'min' => 5,
		'max' => 25
	],
	'body' => [
		'required' => true,
		'min' => 5,
		'max' => 25
	],
	'user_email' => [
		'required' => true,
		'email' => true
	],
	'pw' => [
		'required' => true,
		'checkPassword' => true
	]
];


class Validator2 extends Validator
{
	public function listErrors(string $data_key)
	{
		$res = '';
		foreach($this->errors[$data_key] as $error) {
			$res .= "new tamplate errors: <b>{$error}</b><br>";
		}
		return $res;
	}
}

$validator2 = new Validator2();
$validator2->validate($data, $rules);

if($validator2->hasErrors())
{
	echo $validator2->listErrors('title');
}