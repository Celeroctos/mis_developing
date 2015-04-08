<?php

class MedcardTable extends Table {

	/**
	 * @var string - Default search mode, set it to "lis" if you
	 * 	want fetch rows from laboratory medcards
	 */
	public $mode = "mis";

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
		if ($this->mode == "lis") {
			$model = new LMedcard();
		} else {
			$model = new LMedcard2();
		}
		if (!$model instanceof ActiveRecord) {
			throw new CException("Medcard model must be an instance of ActiveRecord class");
		}
		$this->provider = $model->getDefaultTableProvider();
		$this->provider->getPagination()->pageLimit = 10;
	}
} 