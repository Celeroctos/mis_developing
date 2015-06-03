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
		print MedcardTypeHtml::renderByType($this->element->getAttribute('type'), 'test', []);
	}
}