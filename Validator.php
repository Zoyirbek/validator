<?php

namespace RzValidator;

class Validator
{
	protected $errors = [];
	protected $rules_list = ['required', 'min', 'max', 'email', 'checkPassword'];
	protected $messages = [
        'required' => 'The :title: is required',
        'min' => 'The :title: must be a minimun :value: characters',
        'max' => 'The :title: must be a maximum :value: characters',
        'email' => 'Not valid email',
        'checkPassword' => 'Password confirmation failed',
    ];

	public function validate(array $data = [], array $rules = [], $debug=0)
	{
		foreach ($data as $key => $value) {
			if(isset($rules[$key])) {
				$this->check([
					'key' => $key,
					'value' => $value,
					'rules' => $rules[$key]
				], $debug);
			}
		}
		return $this;
	}

	protected function check(array $data, $debug=0)
	{
		foreach($data['rules'] as $rule => $rule_value) {
			if(!in_array($rule, $this->rules_list)) continue;
			if($debug)
			{
				echo '<pre>' . print_r('<b>' . $data['key'] . '</b>' . ' => ' . $rule . ' > ' . $rule_value, 1) . '<pre>';
				echo '<pre>' . var_dump($this->$rule($data['value'], $rule_value)) . '<pre>';
			}
			if(!$debug) 
			{
				if(!$this->$rule($data['value'], $rule_value))
				{
					$this->addError($data['key'], str_replace(
						[':title:', ':value:'], 
						[$data['key'], $rule_value], 
					$this->messages[$rule]));
				}
			}
		}
	}

	protected function addError($data_key, $error_message)
	{
		$this->errors[$data_key][] = $error_message;
	}

	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function listErrors(string $data_key)
	{
		$res = '';
		$res .= "<ul>";
		foreach($this->errors[$data_key] as $error) {
			$res .= "<li>{$error}</li>";
		}
		$res .= "</ul>";
		return $res;
	}

	protected function required($data, $rule): bool // array | string
	{
		return (!empty($data) || $data !== '');		
	}
	protected function min(string $data, $rule): bool
	{
		return mb_strlen(trim($data), 'UTF-8') >= $rule;
	}
	protected function max(string $data, $rule): bool
	{
		return mb_strlen(trim($data), 'UTF-8') <= $rule;
	}
	protected function email(string $data, $rule): bool
	{
		return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
	}
	protected function checkPassword(array $data, $rule): bool
	{
		if(!isset($data[0]) || !isset($data[1]) || $data[0] === '' ||  $data[1] === '') return false;
		return $data[0] === $data[1];
	}
}




