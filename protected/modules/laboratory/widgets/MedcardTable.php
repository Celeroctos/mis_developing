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
	public $pk = "number";

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

	/**
	 * @inheritdoc
	 */
    public function run() {
		if (!isset(self::$models[$this->mode])) {
			throw new CException("Unresolved search mode \"{$this->mode}\"");
		}
		$this->table = new self::$models[$this->mode]();
		return parent::run();
    }

	private static $models = [
		"mis" => "LMedcard2",
		"lis" => "LMedcard"
	];
} 