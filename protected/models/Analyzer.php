<?php

class Analyzer extends GActiveRecord {

	public function getForm() {

	}

	public function rules() {
		return [
			[ "short_name", "length", "max" => 20 ],
			[ "name", "length", "max" => 255 ]
		];
	}

	public function tableName() {
		return "lis.analysis_type_parameter";
	}
}