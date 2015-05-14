<?php
/**
 * Класс для работы с отделениями
 */
class AnalysisParameter extends GActiveRecord {

	public function getForm() {
		return new AnalysisParameterForm();
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
