<?php

class MedcardTable extends Table {

	public $header = [
		"card_number" => [
			"label" => "Номер",
			"style" => "width: 10%"
		],
		"fio" => [
			"label" => "ФИО",
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
			"label" => "Телефон"
		]
	];

	public $primaryKey = "card_number";
	public $orderBy = "card_number";
	public $id = "medcard-table";
	public $pageLimit = 10;
	public $click = "MedcardSearch.click";

	/**
	 * Initialize table widget
	 */
	public function init() {
		$this->provider = LMedcard::model()->getMedcardSearchTableProvider();
	}
} 