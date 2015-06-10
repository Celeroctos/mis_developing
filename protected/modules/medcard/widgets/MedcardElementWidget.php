<?php

class MedcardElementWidget extends Widget {

	/**
	 * @var MedcardElementPatientEx|MedcardElementEx class instance of
	 * 	active record class
	 */
	public $element;

	/**
	 * @var string prefix for category identifier
	 *  generator
	 */
	public $prefix = '';

	public function init() {
		if (empty($this->element)) {
			throw new CException('Medcard element must not be empty');
		} else if (!$this->element instanceof CActiveRecord) {
			throw new CException('Medcard element must be an instance of ActiveRecord class');
		}
	}

	public function run() {
		if (!empty($this->element->{'size'}) && $this->element->{'size'} > 0) {
			$width = $this->element->{'size'};
		} else {
			$width = null;
		}
		print Html::openTag('table', [
			'class' => 'element-wrapper'
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
		print MedcardHtml::renderByType($this->element->{'type'}, 'test', $this->prepareElement($this->element));
		if ($this->getConfig('showDynamic')) {
			print Html::closeTag('span');
		}
		print Html::closeTag('td');
		$this->renderLabelAfter();
		print Html::closeTag('tr');
		print Html::closeTag('table');
	}

	protected function renderLabelBefore() {
		print Html::tag('td', [ 'align' => 'middle' ], Html::tag('label', [ 'class' => 'element-label-before' ],
			$this->element->{'label'}
		));
	}

	protected function renderLabelAfter() {
		print Html::tag('td', [ 'align' => 'middle' ], Html::tag('label', [ 'class' => 'element-label-after' ],
			$this->element->{'label_after'}
		));
	}

	protected function prepareElement(CActiveRecord $element) {
		$type = $element->{'type'};
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
			$parameters['value'] = $element->{'default_value'};
		}
		return $parameters;
	}

	protected function prepareList() {
		$parameters = [];
		if (!$guide = $this->element->{'guide_id'}) {
			$data = [ -2 => 'Отсутствует справочник' ];
		} else {
			$data = MedcardGuideValue::model()->getRows(false, $guide);
		}
		if ($this->element->{'type'} == MedcardElementEx::TYPE_EXCHANGE) {
			unset($data['-3']);
		}
		if ($this->element->{'default_value'}) {
			$parameters['selected'] = $this->element->{'default_value'};
		}
		$parameters['data'] = MedcardHtml::listData($data, 'id', 'value');
		return $parameters;
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
			$this->_config = json_decode($this->element->{'config'}, true);
		}
		if (isset($this->_config[$key])) {
			return $this->_config[$key];
		}
		return $default;
	}

	private function createKey($prefix, $letter) {
		return $prefix.'_'.MedcardHtml::createHash($this->element, $this->prefix, $letter);
	}

	private $_config = null;
}