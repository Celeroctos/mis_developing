<?php

class DirectionTable extends Table {

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

	public $controls = [
		"direction-repeat-icon" => [
			"class" => "glyphicon glyphicon-arrow-right",
			"tooltip" => "Отправить на повторный забор"
		]
	];

	public $orderBy = "id";
	public $searchCriteria = "status <> 4";
	public $pageLimit = 25;

	public function init() {
		$this->provider = LDirection::model()->getTreatmentTableProvider();
	}
}