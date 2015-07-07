<?php

class MedcardHtml extends Html {

	public static $defaultIndexes = [
		'name', 'value', 'options',
	];

	public static $listIndexes = [
		'name', 'select', 'data', 'options',
	];

	public static $tableIndexes = [
		'name', 'config', 'options',
	];

	public static function renderByType($type, $name, $parameters = [], $capture = false) {
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
            if (is_string($parameters['select'])) {
                $parameters['select'] = json_decode($parameters['select'], true);
            }
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
		return static::renderInternal($strategy, $name, $fix, $capture);
	}

	public static function createHash($element, $prefix = '', $letter = 'f', $glue = '|') {
		return $letter.'_'.$prefix.'_'.static::createPath($element, $glue).'_'.$element['id'];
	}

	public static function createKey(CActiveRecord $element) {
		return
			$element->{'medcard_id'}.'|'.
			$element->{'greeting_id'}.'|'.
			$element->{'path'}.'|'.
			$element->{'categorie_id'}.'|'.
			$element->{'id'};
	}

	public static function createPath($element, $glue = '|') {
        if ($element instanceof CActiveRecord) {
            if (!$element->hasAttribute('path')) {
                throw new CException('Can\'t create path for element without [path] field');
            } else {
                $path = $element->getAttribute('path');
            }
        } else if (is_array($element)) {
            if (!isset($element['path'])) {
                throw new CException('Can\'t create path for element without [path] field');
            } else {
                $path = $element['path'];
            }
        } else {
            throw new CException('Unresolved element type, requires CActiveRecord or array');
        }
		return implode($glue, explode('.', $path));
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
		$config = [];
		if (isset($data['-3'])) {
			$config += [ '-3' => [ 'class' => 'hidden' ] ];
		}
		$addon = UniqueGenerator::generate('addon');
        if (!isset($options['readonly'])) {
            print parent::openTag('div', ['class' => 'input-group']);
        }
        print parent::dropDownList($name, $select, $data, $options + [
				'class' => 'form-control', 'options' => $config, 'aria-describedby' => $addon,
			]);
        if (!isset($options['readonly'])) {
            $js = 'var _s = $(this).parent(".input-group").children("select"); window["applyInsertForSelect"] && window["applyInsertForSelect"].call(_s, _s)';
            print parent::tag('span', [ 'class' => 'input-group-addon', 'style' => 'cursor: pointer;', 'id' => $addon, 'onclick' => $js ], parent::tag('span', [
                'class' => 'glyphicon glyphicon-plus'
            ], ''));
            print parent::closeTag('div');
        }
        print parent::closeTag('div');
		return null;
	}

	/**
	 * Render multiple element with next HTML structure
	 *
	 * <div class="multiple">
	 *   <select multiple="multiple" name="test[]" id="test" class="multiple-value form-control">
	 *     <option value="...">...</option>
	 *   </select>
	 *   <div class="multiple-control text-left">
	 *     <button class="btn btn-default multiple-collapse-button" type="button">
	 *       <span>Развернуть / Свернуть</span>
	 *     </button>
	 *     <button class="btn btn-default multiple-down-button" type="button">
	 *       <span class="glyphicon glyphicon-arrow-down"></span>
	 *     </button>
	 *     <button class="btn btn-default multiple-up-button" type="button">
	 *       <span class="glyphicon glyphicon-arrow-up"></span>
	 *     </button>
	 *     <button class="btn btn-default multiple-insert-button" type="button">
	 *       <span class="glyphicon glyphicon-plus"></span>
	 *     </button>
	 *   </div>
	 *   <div class="multiple-container form-control"></div>
	 * </div>
	 *
	 * @param $name string name of multiple element
	 * @param $select array with selected values
	 * @param $data array with list data
	 * @param $options array with html options
	 *
	 * @return null, catch output buffer to get string
	 */
	public static function multipleInput($name, $select, $data, $options = []) {
        if (isset($data['-3'])) {
            $insert = true;
        } else {
            $insert = false;
        }
        unset($data['-3']);
		print parent::openTag('div', [ 'class' => 'multiple' ]);
		print parent::dropDownList($name, $select, $data, $options + [
				'multiple' => 'multiple',
				'class' => 'multiple-value form-control',
			]);
		print parent::openTag('div', [ 'class' => 'multiple-control text-left' ]);
		print parent::tag('button', [
			'class' => 'btn btn-default multiple-collapse-button',
			'type' => 'button',
		], '<span>Развернуть / Свернуть</span>');
		print parent::tag('button', [
			'class' => 'btn btn-default multiple-down-button',
			'type' => 'button',
		], '<span class="glyphicon glyphicon-arrow-down"></span>');
		print parent::tag('button', [
			'class' => 'btn btn-default multiple-up-button',
			'type' => 'button',
		], '<span class="glyphicon glyphicon-arrow-up"></span>');
		if ($insert) {
			print parent::tag('button', [
				'class' => 'btn btn-default multiple-insert-button',
				'type' => 'button',
			], '<span class="glyphicon glyphicon-plus"></span>');
		}
		print parent::closeTag('div');
		print parent::tag('div', [ 'class' => 'multiple-container form-control' ], '');
		print parent::closeTag('div');
		return null;
	}

    public static function selectInput($name, $select, $data, $options = []) {
        if (!isset($options['disabled'])) {
            return static::dropDownInput($name, $select, $data, $options + [
                    'class' => 'form-control selectpicker',
                    'data-live-search' => 'true',
                    'multiple' => 'true',
                    'data-ignore' => 'multiple'
                ]);
        } else {
            $result = [];
            foreach ($data as $value => $label) {
                if (in_array($value, $select)) {
                    $result[$value] = $label;
                }
            }
            return static::dropDownInput($name, [], $result, $options + [
                    'multiple' => 'true',
                    'data-ignore' => 'multiple'
                ]);
        }
    }

	public static function tableInput($name, $config, $options = []) {
		$cols = $config['numCols'];
		$rows = $config['numRows'];
		if (isset($config['rows'])) {
			$labels = $config['rows'];
		} else {
			$labels = [];
		}
		print parent::openTag('table', [ 'width' => '100%',
				'class' => 'table table-bordered table-striped table-condensed controltable'
			]);
		if (isset($config['cols']) && !empty($config['cols'])) {
			print parent::openTag('thead');
			print parent::openTag('tr');
			if (!empty($labels)) {
				print parent::tag('td', [ 'align' => 'left' ]);
			}
			foreach ($config['cols'] as $c) {
				print parent::tag('td', [ 'align' => 'left', 'class' => 'control-table-header' ],
					parent::tag('b', [], $c)
				);
			}
			print parent::closeTag('tr');
			print parent::closeTag('thead');
		}
		$values = [];
		foreach ($config['values'] as $key => $val) {
			if (count($key = explode('_', $key)) != 2) {
				continue;
			}
			if (!isset($values[$key[0]])) {
				$values[$key[0]] = [ $key[1] => $val ];
			} else {
				$values[$key[0]][$key[1]] = $val;
			}
		}
		print parent::openTag('tbody');
		for ($i = 0; $i < $rows; $i++) {
			print parent::openTag('tr');
			if (isset($labels[$i])) {
				print parent::tag('td', [ 'class' => 'control-table-header' ],
					parent::tag('b', [], $labels[$i])
				);
			}
			for ($j = 0; $j < $cols; $j++) {
				print parent::tag('td', [
                    'class' => 'controlTableContentCell content-'.($i + 1).'_'.$j,
					'height' => '25px',
                    'width' => '75px',
				], isset($values[$i][$j]) ? $values[$i][$j] : '');
			}
			print parent::closeTag('tr');
		}
		print parent::closeTag('tbody');
		print parent::closeTag('table');
        if (!isset($options['print'])) {
            print parent::textField($name, json_encode($config), $options + [
                    'style' => 'display: none;',
                ]);
        }
		return null;
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
        if (is_array($config)) {
            $config = json_encode($config);
        }
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
	 *
	 * @return null simply null, capture output buffer
	 * 	to get string content
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
		print parent::openTag('table', [
			'class' => 'exchange twoColumnList',
			'width' => '100%',
		]);
		print parent::openTag('tr');
		print parent::tag('td', [
			'width' => 'calc(50% - 20px)',
            'valign' => 'top',
		], parent::dropDownList(null, null, $left, $options + [
				'multiple' => 'multiple',
				'class' => 'form-control twoColumnListFrom col-xs-5 no-padding',
				'data-ignore' => 'multiple',
			])
		);
		print parent::openTag('td', [
			'width' => '40px',
            'valign' => 'top',
		]);
        print parent::openTag('div', [
            'class' => 'TCLButtonsContainer btn-group-vertical',
            'style' => 'width: 40px;',
        ]);
		print parent::tag('span', [ 'class' => 'btn btn-default btn-block twoColumnAddBtn' ],
			parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-right' ], '')
		);
        if (isset($options['growable'])) {
            print parent::tag('span', [ 'class' => 'btn btn-default btn-block twoColumnAddBtn' ],
                parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-right' ], '')
            );
        }
		print parent::tag('span', [ 'class' => 'btn btn-default btn-block twoColumnRemoveBtn' ],
			parent::tag('span', [ 'class' => 'glyphicon glyphicon-arrow-left' ], '')
		);
        print parent::closeTag('div');
		print parent::closeTag('td');
		print parent::tag('td', [
            'width' => '50%',
            'valign' => 'top',
		], parent::dropDownList($name, null, $right, $options + [
				'multiple' => 'multiple',
				'class' => 'form-control twoColumnListTo col-xs-5 no-padding',
				'data-ignore' => 'multiple',
			])
		);
		print parent::closeTag('tr');
		print parent::closeTag('table');
		return null;
	}

	protected static function renderInternal($method, $name, $parameters = [], $capture = true) {
		if (!method_exists(__CLASS__, $method)) {
			throw new CException('Unresolved medcard type render method (' . $method . ')');
		} else if ($capture) {
			ob_start();
		}
		print call_user_func_array([ __CLASS__, $method ], [ $name ] + $parameters);
		return $capture ? ob_get_clean() : null;
	}
}