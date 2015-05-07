<?php

class Analyzer extends GActiveRecord {

	public function getForm() {
		return new AnalyzerForm();
	}

	public function rules() {
		return $this->getForm()->backward();
	}

	public function tableName() {
		return "lis.analyzer";
	}
}