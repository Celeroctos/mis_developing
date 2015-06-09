<?php

class MedcardHtml extends CHtml {

	public static $defaultIndexes = [
		'name', 'value', 'options',
	];

	public static $listIndexes = [
		'name', 'select', 'data', 'options',
	];

	public static $tableIndexes = [
		'name', 'config', 'options',
	];

	public static function renderByType($type, $name, $parameters = []) {
		$fix = [];
		if (!isset(MedcardElementEx::$render[$type])) {
			throw new CException('Unresolved medcard type ('. $type .')');
		} else {
			$strategy = MedcardElementEx::$render[$type];
		}
		if (in_array($type, MedcardElementEx::$listTypes)) {
			$parameters += [
				'select' => [],
				'data' => [],
				'options' => [],
			];
			$indexes = static::$listIndexes;
		} else if (in_array($type, MedcardElementEx::$tableTypes)) {
			$parameters += [
				'config' => [],
				'options' => [],
			];
			$indexes = static::$tableIndexes;
		} else {
			$parameters += [
				'options' => [],
			];
			$indexes = static::$defaultIndexes;
		}
		foreach ($indexes as $i) {
			$fix[] = $parameters[$i];
		}
		return static::renderInternal($strategy, $name, $fix);
	}

	public static function createKey(CActiveRecord $element) {
		return
			$element->{'medcard_id'}.'|'.
			$element->{'greeting_id'}.'|'.
			$element->{'path'}.'|'.
			$element->{'categorie_id'}.'|'.
			$element->{'id'};
	}

	public static function createPath(CActiveRecord $element) {
		return implode('', explode('.', $element->{'path'}));
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
		if (isset($data['-3'])) {
		}
		return static::dropDownList($name, $select, $data, $options + [
				'class' => 'form-control', 'options' => [

				]
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

	/**
	 * Render date input field for element, it takes [data-config] attribute
	 * from HTML options and put it to datepicker plugin's configuration
	 *
	 * @param $name string name of date element
	 * @param $value string default value
	 * @param $options array with html options
	 *
	 * @return string with just renderer element
	 */
	public static function dateInput($name, $value = '', $options = []) {
		if (isset($options['data-config'])) {
			$config = $options['data-config'];
		} else {
			$config = [];
		}
		unset($options['data-config']);
		$js = "$(this).datepicker($config).datepicker(\"show\");";
		return static::inputField('text', $name, $value, $options + [
				'class' => 'form-control datepicker-fix', 'onclick' => $js
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
			if (!in_array($key, $select)) {
				$left[$key] = $value;
			} else {
				$right[$key] = $value;
			}
		}
		print parent::openTag('div', [ 'class' => 'exchange col-xs-12 no-padding' ]);
		print parent::tag('div', [ 'class' => 'col-xs-5 no-padding' ],
			parent::dropDownList(null, null, $left, $options + [
				'multiple' => 'multiple',
				'class' => 'form-control twoColumnListFrom col-xs-5 no-padding',
				'data-ignore' => 'multiple',
			])
		);
		print parent::openTag('div', [ 'class' => 'TCLButtonsContainer col-xs-1 text-center' ]);
		print parent::tag('span', [ 'class' => 'btn btn-default btn-sm btn-block twoColumnRemoveBtn' ],
			parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-left' ], '')
		);
		print parent::tag('span', [ 'class' => 'btn btn-default btn-sm btn-block twoColumnAddBtn' ],
			parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-right' ], '')
		);
		print parent::closeTag('div');
		print parent::tag('div', [ 'class' => 'col-xs-5 no-padding' ],
			parent::dropDownList($name, null, $right, $options + [
					'multiple' => 'multiple',
					'class' => 'form-control twoColumnListTo col-xs-5 no-padding',
					'data-ignore' => 'multiple',
				])
		);
		print parent::closeTag('div');
	}

	protected static function renderInternal($method, $name, $parameters = []) {
		if (!method_exists(__CLASS__, $method)) {
			throw new CException('Unresolved medcard type render method (' . $method . ')');
		} else {
			ob_start();
		}
		print call_user_func_array([ __CLASS__, $method ], [ $name ] + $parameters);
		return ob_get_clean();
	}
}