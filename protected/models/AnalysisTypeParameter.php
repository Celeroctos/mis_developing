<?php
/**
 * Класс для работы с отделениями
 */
class AnalysisTypeParameter extends GActiveRecord {

	public function getForm() {
		return new AnalysisTypeParameterForm();
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
