<?php

class MedcardTable extends Table {

	/**
	 * @inheritdoc
	 */
	public $header = [
		"number" => [
			"label" => "Номер ЛКП",
			"style" => "width: 15%"
		],
		"fio" => [
			"label" => "ФИО пациента",
			"style" => "width: 25%"
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

	/**
	 * @inheritdoc
	 */
	public $primaryKey = "number";

	/**
	 * @inheritdoc
	 */
	public $sort = "number";

	/**
	 * @inheritdoc
	 */
	public $id = "medcard-table";

	/**
	 * @inheritdoc
	 */
	public $limit = 10;

	/**
	 * @inheritdoc
	 */
	public $click = "MedcardSearch.click";

	public function init() {
		$this->provider = LMedcard::model()->getMedcardSearchTableProvider();
	}
} 