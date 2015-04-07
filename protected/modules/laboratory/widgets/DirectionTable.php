<?php

class DirectionTable extends Table {

	/**
	 * @var string - Condition for [DbCriteria]
	 * @see DbCriteria::createWhere
	 */
	public $where = "status <> 3";

	/**
	 * @var array - Array with table's header
	 * 	configuration
	 */
	public $header = [
		"id" => [
			"label" => "#",
			"style" => "width: 15%"
		],
		"card_number" => [
			"label" => "Номер карты",
			"style" => "width: 25%"
		],
		"status" => [
			"label" => "Статус",
			"style" => "width: 20%"
		],
		"sender_id" => [
			"label" => "Направитель",
			"style" => "width: 15%"
		],
		"analysis_type_id" => [
			"label" => "Тип анализа"
		]
	];

	/**
	 * @var string - Default primary key
	 */
	public $pk = "id";

	/**
	 * @var string - Default table order
	 */
	public $sort = "id";

	/**
	 * @var int - Count of items to display
	 */
	public $limit = 25;

	/**
	 * Run widget to return it's just rendered content
	 * @return string - Just rendered content
	 */
	public function init() {
		$this->provider = (new LDirection("laboratory.treatment.grid"))
			->getTableProvider();
		$this->provider->getCriteria()->addCondition($this->where);
	}
}