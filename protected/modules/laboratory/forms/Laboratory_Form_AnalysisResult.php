<?php

class Laboratory_Form_AnalysisResult extends CFormModel {

	public $id;
	public $result;

	public function rules() {
		return [
			[ 'result, id', 'required' ]
		];
	}
}