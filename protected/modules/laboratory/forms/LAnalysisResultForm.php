<?php

class LAnalysisResultForm extends CFormModel {

	public $id;
	public $result;

	public function rules() {
		return [
			[ 'result, id', 'required' ]
		];
	}
}