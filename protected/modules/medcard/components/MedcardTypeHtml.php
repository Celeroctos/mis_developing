<?php

class MedcardTypeHtml extends CHtml {

	public static $listTypes = [
		MedcardElementEx::TYPE_DROPDOWN,
		MedcardElementEx::TYPE_MULTIPLE,
	];

	public static $tableTypes = [
		MedcardElementEx::TYPE_TABLE,
	];

	public static function renderByType($type, $name, $parameters = []) {
		if (!isset(MedcardElementEx::$render[$type])) {
			throw new CException('Unresolved medcard type ('. $type .')');
		}
		if (in_array($type, static::$listTypes)) {
			return static::renderEx(MedcardElementEx::$render[$type], $name, $parameters + [
					'select' => '', 'data' => []
				]);
		} else if (in_array($type, static::$tableTypes)) {
			return static::renderEx(MedcardElementEx::$render[$type], $name, $parameters + [
					'config' => []
				]);
		} else {
			return static::renderEx(MedcardElementEx::$render[$type], $name, $parameters);
		}
	}

	public static function renderEx($method, $name, $parameters = []) {
		if (method_exists(__CLASS__, $method)) {
			return call_user_func_array([ __CLASS__, $method ], [ $name ] + $parameters);
		} else {
			throw new CException('Unresolved medcard type render method ('. $method .')');
		}
	}

	public static function textInput($name, $value = '', $options = []) {
		return static::textField($name, $value, $options + [
				'class' => 'form-control'
			]);
	}

	public static function textAreaInput($name, $value = '', $options = []) {
		return static::textArea($name, $value, $options + [
				'class' => 'form-control',
				'style' => 'resize: vertical'
			]);
	}

	public static function dropDownInput($name, $select, $data, $options = []) {
		return static::dropDownList($name, $select, $data, $options + [
				'class' => 'form-control'
			]);
	}

	public static function multipleInput($name, $select, $data, $options = []) {
		return static::dropDownList($name, $select, $data, $options + [
				'form-control' => 'true',
				'multiple' => 'true',
			]);
	}

	public static function tableInput($name, $config, $options = []) {
		return 'table123';
	}

	public static function numberInput($name, $value = '', $options = []) {
		return static::numberField($name, $value, $options + [
				'class' => 'form-control'
			]);
	}

	public static function dateInput($name, $value = '', $options = []) {
		return static::inputField('date', $name, $value, $options + [
				'class' => 'form-control'
			]);
	}

	public static function exchangeInput() {
		return 'exchange123';
	}
}