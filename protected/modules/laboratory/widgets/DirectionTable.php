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
	 * @var array - Array with elements controls buttons, like edit
	 * 	or remove. Array's key is class for [a] tag and value is
	 * 	class for [span] tag like glyphicon or button
	 * @see renderControls
	 */
	public $controls = [
		"direction-repeat-icon" => [
			"class" => "glyphicon glyphicon-repeat",
			"tooltip" => "Отправить на повторный анализ"
		]
	];

	/**
	 * @var string - Default table order
	 */
	public $orderBy = "id";

	/**
	 * @var int - Count of items to display
	 */
	public $pageLimit = 25;

	/**
	 * Run widget to return it's just rendered content
	 * @return string - Just rendered content
	 */
	public function init() {
		$this->provider = (new LDirection("laboratory.treatment.grid"))
			->getTableProvider();
		$this->provider->getCriteria()->addCondition($this->where);
		$this->provider->getPagination()->pageLimit = $this->pageLimit;
		$this->provider->getCriteria()->order = $this->orderBy;
	}
}