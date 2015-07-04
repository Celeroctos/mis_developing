<?php

class MedcardElementWidget extends Widget {

    const DEFAULT_SCALE = 10;

	/**
	 * @var CActiveRecord|array class instance of
	 * 	active record class
	 */
	public $element;

	/**
	 * @var string prefix for category identifier
	 *  generator
	 */
	public $prefix = '';

    /**
     * @var int default size of element
     */
    public $size = 300;

    /**
     * @var int scale factor for size parameter, by default it
     *  takes from database [mis.settings], key [lettersInPixel]
     */
    public $scale;

    /**
     * @var MedcardCategoryWidget category widget, which contains
     *  current element
     */
    public $category = null;

	public function init() {
		if (empty($this->element)) {
			throw new CException('Medcard element must not be empty');
		}
        if (empty($this->scale)) {
            if ($scale = Setting::model()->findByAttributes([ 'name' => 'lettersInPixel' ])) {
                $this->scale = intval($scale['value']);
            } else {
                $this->scale = static::DEFAULT_SCALE;
            }
        }
        if ($this->category != null) {
            $this->_dependencies = $this->category->getDependencies($this->element['id']);
        } else {
            $this->_dependencies = [];
        }
        if ($this->category != null && !isset($this->element['element_id'])) {
            $this->mergeWithHistoryElement($this->category);
        }
	}

    protected function mergeWithHistoryElement(MedcardCategoryWidget $category) {
        $element = MedcardElementPatientEx::model()->fetchHistoryByElement(
            $this->element['id'], $category->getGreeting(), $category->category['path']
        );
        if ($element == null) {
            return ;
        }
        $this->element['default_value'] = $element['value'];
        $this->element['label'] = $element['label_before'];
        $this->element['label_after'] = $element['label_after'];
        $this->element['path'] = $element['path'];
    }

	public function run() {
		if (!empty($this->element['size']) && $this->element['size'] > 0) {
			$width = $this->element['size'] * $this->scale;
		} else {
			$width = $this->size;
		}
        if ($this->element['type'] == MedcardElementEx::TYPE_DROPDOWN) {
            $width += 37; # Width of small bootstrap button with plus glyphicon
        } else if ($this->element['type'] == MedcardElementEx::TYPE_EXCHANGE) {
            $width = $width * 2 + 40; # Two columns width + Small bootstrap button, for 40px see [MedcardHtml::exchangeInput]
        }
        if ($this->category != null) {
            if ($this->category->getDependent($this->element['id']) == MedcardElementDependencyEx::ACTION_SHOW) {
                $style = 'display: none;';
            } else {
                $style = null;
            }
        } else {
            $style = null;
        }
        print Html::openTag('div', [
            'class' => 'medcard-element-wrapper',
            /* 'onmouseenter' => '$(this).tooltip("show")',
            'data-original-title' => $this->element['path'], */
            'style' => $style,
        ]);
		print Html::openTag('table', [
			'class' => 'medcard-element',
		]);
		print Html::openTag('tr');
		$this->renderLabelBefore();
		print Html::openTag('td', [
			'width' => $width
		]);
		if ($this->getConfig('showDynamic')) {
			print Html::openTag('span', [
				'class' => 'showDynamicWrap'
			]);
			print Html::tag('span', [
				'class' => 'showDynamicIcon glyphicon glyphicon-eye-open',
				'title' => 'Динамика изменения параметра',
			], '');
		}
		print MedcardHtml::renderByType($this->element['type'], $this->createName(), $this->prepareElement($this->element));
		if ($this->getConfig('showDynamic')) {
			print Html::closeTag('span');
		}
		print Html::closeTag('td');
        if ($this->element['allow_add']) {
            print Html::openTag('td');
            print Html::tag('button', [
                'type' => 'button',
                'id' => 'ba_'.$this->prefix.'_'.$this->element['guide_id'].'_'.$this->prefix.$this->element['id'],
                'class' => 'btnAddValues btn btn-default btn-sm'
            ], Html::tag('span', [
                'class' => 'glyphicon glyphicon-plus'
            ]));
            print Html::closeTag('td');
        }
		$this->renderLabelAfter();
		print Html::closeTag('tr');
		print Html::closeTag('table');
        print Html::closeTag('div');
	}

	protected function renderLabelBefore() {
		print Html::tag('td', [ 'align' => 'middle' ], Html::tag('label', [ 'class' => 'element-label-before' ],
			$this->element['label']
		));
	}

	protected function renderLabelAfter() {
		print Html::tag('td', [ 'align' => 'middle' ], Html::tag('label', [ 'class' => 'element-label-after' ],
			$this->element['label_after']
		));
	}

	protected function prepareElement($element) {
		$type = $element['type'];
		if (in_array($type, MedcardElementEx::$listTypes)) {
			$parameters = $this->prepareList();
		} else if (in_array($type, MedcardElementEx::$tableTypes)) {
			$parameters = $this->prepareTable();
		} else if ($type == MedcardElementEx::TYPE_DATE) {
			$parameters = $this->prepareDate();
		} else if ($type == MedcardElementEx::TYPE_NUMBER) {
			$parameters = $this->prepareNumber();
		} else {
			$parameters = [];
		}
        if (!in_array($type, MedcardElementEx::$listTypes) &&
            !in_array($type, MedcardElementEx::$tableTypes)
        ) {
            if (isset($element['value'])) {
                $parameters['value'] = $element['value'];
            } else {
                $parameters['value'] = $element['default_value'];
            }
        }
        if (!isset($parameters['options'])) {
            $parameters['options'] = [];
        }
        $parameters['options']['id'] = MedcardHtml::createHash($this->element, $this->prefix, 'f');
		return $parameters;
	}

	protected function prepareList() {
		$parameters = [];
		if (!$guide = $this->element['guide_id']) {
			$data = [ -2 => 'Отсутствует справочник' ];
		} else {
			$data = MedcardGuideValue::model()->getRows(false, $guide);
		}
		if ($this->element['type'] == MedcardElementEx::TYPE_EXCHANGE) {
			unset($data['-3']);
		}
        if (isset($this->element['value'])) {
            if ($this->element['value']) {
                $parameters['select'] = $this->element['value'];
            }
        } else if ($this->element['default_value']) {
			$parameters['select'] = $this->element['default_value'];
		}
		$parameters['data'] = MedcardHtml::listData($data, 'id', 'value');
        if (empty($this->_dependencies)) {
            return $parameters;
        }
        $js = '';
        foreach ($this->_dependencies as $dependency) {
            $js .= $this->createDependencyScript($dependency);
        }
        $parameters['options'] = [
            'onchange' => preg_replace('/[\r\n\t ]+/', ' ', $js)
        ];
		return $parameters;
	}

    protected function createDependencyScript($dependency) {
        $value = $dependency['value_id'];
        if ($dependency['action'] == MedcardElementDependencyEx::ACTION_HIDE) {
            $if = 'hide';
            $else = 'show';
        } else if ($dependency['action'] == MedcardElementDependencyEx::ACTION_SHOW) {
            $if = 'show';
            $else = 'hide';
        } else {
            return '';
        }
        $id = MedcardHtml::createHash([ 'id' => $dependency['dep_element_id'], 'path' => $dependency['dep_path'], ], $this->prefix, 'f');
        return 'if ($(this).val() == '. $value .') $(document.getElementById("'. $id .'")).parents(".medcard-element-wrapper:eq(0)").'. $if .'(); else $(document.getElementById("'. $id .'")).parents(".medcard-element-wrapper:eq(0)").'. $else .'(); ';
    }

	protected function prepareTable() {
		$parameters = [
			'config' => [
				'numCols' => $this->getConfig('numCols', 3),
				'numRows' => $this->getConfig('numRows', 3),
				'rows' => $this->getConfig('rows', []),
				'cols' => $this->getConfig('cols', []),
				'values' => $this->getConfig('values', []),
			]
		];
		return $parameters;
	}

	protected function prepareDate() {
		$config = [
			'format' => 'yyyy-mm-dd',
			'language' => 'ru-RU',
			'todayBtn' => 'linked',
			'todayHighlight' => 'true',
			'autoclose' => 'true',
			'orientation' => 'bottom top',
		];
		if ($min = $this->getConfig('minValue')) {
			$config['startDate'] = $min;
		}
		if ($max = $this->getConfig('maxValue')) {
			$config['endDate'] = $max;
		}
		return [ 'options' => [ 'data-config' => json_encode($config) ] ];
	}

	protected function prepareNumber() {
		$options = [];
		if ($min = $this->getConfig('minValue')) {
			$options['min'] = $min;
		}
		if ($max = $this->getConfig('maxValue')) {
			$options['max'] = $max;
		}
		if ($step = $this->getConfig('step')) {
			$options['step'] = $step;
		}
		return [ 'options' => $options ];
	}

	protected function getConfig($key, $default = null) {
		if ($this->_config == null) {
			$this->_config = json_decode($this->element['config'], true);
		}
		if (isset($this->_config[$key])) {
			return $this->_config[$key];
		}
		return $default;
	}

    private function createName() {
        return 'FormTemplateDefault[f'.preg_replace('/\./', '|', $this->element['path']).'_'.$this->element['id'].']';
    }

	private function createKey($prefix = null, $letter = '') {
		return $prefix.'_'.MedcardHtml::createHash($this->element, $this->prefix, $letter);
	}

    private $_dependencies;
	private $_config = null;
}