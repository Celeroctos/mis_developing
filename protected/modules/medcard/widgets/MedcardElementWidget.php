<?php

class MedcardElementWidget extends Widget {

	/**
	 * @var MedcardElementPatientEx|MedcardElementEx class instance of
	 * 	active record class
	 */
	public $element;

	public function init() {
		if (empty($this->element)) {
			throw new CException('Medcard element must not be empty');
		} else if (!$this->element instanceof CActiveRecord) {
			throw new CException('Medcard element must be an instance of ActiveRecord class');
		}
	}

	public function run() {
		print MedcardHtml::openTag('div', [
			'class' => 'element-wrapper'
		]);
		print MedcardHtml::tag('span', [ 'class' => 'element-label-before' ],
			$this->element->{'label'}
		);
		if ($this->getConfig('showDynamic')) {
			print MedcardHtml::openTag('span', [
				'class' => 'showDynamicWrap'
			]);
			print MedcardHtml::tag('span', [
				'class' => 'showDynamicIcon glyphicon glyphicon-eye-open',
				'title' => 'Динамика изменения параметра',
			], '');
		}
		print MedcardHtml::renderByType($this->element->{'type'}, 'test', $this->prepareElement($this->element));
		if ($this->getConfig('showDynamic')) {
			print MedcardHtml::closeTag('span');
		}
		print MedcardHtml::tag('span', [ 'class' => 'element-label-after' ],
			$this->element->{'label_after'}
		);
		print MedcardHtml::closeTag('div');
	}

	protected function prepareElement(CActiveRecord $element) {
		$parameters = [];
		$type = $element->{'type'};
		if (in_array($type, MedcardElementEx::$listTypes)) {
			$parameters += $this->prepareList();
		} else if ($type == MedcardElementEx::TYPE_DATE) {
			$parameters += $this->prepareDate();
		} else if ($type == MedcardElementEx::TYPE_NUMBER) {
			$parameters += $this->prepareNumber();
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
			throw new CException('Can\'t resolve guide for list element type');
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
		$parameters = [];

		return $parameters;
	}

	protected function prepareDate() {
		$options = [
			'data-date-format' => 'yyyy-mm-dd',
			'data-date-language' => 'ru-RU',
			'data-today-btn' => 'linked',
			'data-today-highlight' => 'true',
			'data-autoclose' => 'true',
		];
		$config = [
			'format' => 'yyyy-mm-dd',
			'language' => 'ru-RU',
			'todayBtn' => 'linked',
			'todayHighlight' => 'true',
			'autoclose' => 'true',
		];
		if ($min = $this->getConfig('minValue')) {
			$options['data-date-start-date'] = $min;
		}
		if ($max = $this->getConfig('maxValue')) {
			$options['data-date-end-date'] = $max;
		}
		return [ 'options' => [ 'data-config' => json_encode($config) ] ];
	}

	protected function prepareNumber() {
		$options = [
		];
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

	private $_config = null;
}