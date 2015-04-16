<?php

class MedcardTable extends Table {

	public $header = [
		"number" => [
			"label" => "Номер",
			"style" => "width: 10%"
		],
		"fio" => [
			"label" => "ФИО пациента",
			"style" => "width: 35%"
		],
		"enterprise" => [
			"label" => "МУ направитель",
			"style" => "width: 20%"
		],
		"birthday" => [
			"label" => "Дата рождения",
			"style" => "width: 15%"
		],
		"phone" => [
			"label" => "Контактный телефон"
		]
	];

	public $primaryKey = "number";
	public $sort = "number";
	public $id = "medcard-table";
	public $limit = 10;
	public $click = "MedcardSearch.click";

	/**
	 * Initialize table widget
	 */
	public function init() {
		$this->provider = LMedcard::model()->getMedcardSearchTableProvider();
	}
} 