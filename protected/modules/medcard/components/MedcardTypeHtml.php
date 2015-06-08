<?php

class MedcardTypeHtml extends CHtml {

	public static $listTypes = [
		MedcardElementEx::TYPE_DROPDOWN,
		MedcardElementEx::TYPE_MULTIPLE,
		MedcardElementEx::TYPE_EXCHANGE,
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

	/**
	 * Render change input type element, it has next HTML structure
	 *
	 * <div class="exchange">
	 *   <select multiple="multiple" data-ignore="multiple" class="form-control twoColumnListFrom">...</select>
	 *     <div class="TCLButtonsContainer">
	 *       <span class = "glyphicon glyphicon-arrow-right twoColumnAddBtn"></span>
	 *       <span class = "glyphicon glyphicon-arrow-left twoColumnRemoveBtn"></span>
	 *     </div>
	 *   <select multiple="multiple" data-ignore="multiple" class="form-control twoColumnListTo">...</select>
	 * </div>
	 *
	 * @param $name string name of exchange element
	 * @param $select array with selected identification numbers
	 * @param $data array with data for option list
	 * @param $options array with html options for left and right lists
	 */
	public static function exchangeInput($name, $select, $data, $options = []) {
		$left = []; $right = [];
		foreach ($data as $key => $value) {
			if (in_array($key, $select)) {
				$left[$key] = $value;
			} else {
				$right[$key] = $value;
			}
		}
		print parent::openTag('div', [ 'class' => 'exchange' ]);
		print parent::dropDownList(null, null, $left, $options + [
				'multiple' => 'multiple',
				'data-ignore' => 'multiple',
				'class' => 'form-control twoColumnListFrom',
			]);
		print parent::openTag('div', [ 'class' => 'TCLButtonsContainer' ]);
		print parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-right twoColumnAddBtn' ], '');
		print parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-left twoColumnRemoveBtn' ], '');
		print parent::closeTag('div');
		print parent::dropDownList($name, null, $right, $options + [
				'multiple' => 'multiple',
				'data-ignore' => 'multiple',
				'class' => 'form-control twoColumnListTo',
			]);
		print parent::closeTag('div');
	}
}