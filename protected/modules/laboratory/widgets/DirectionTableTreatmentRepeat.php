<?php

class DirectionTableTreatmentRepeat extends Table {

	public $header = [
		"id" => [
			"label" => "#",
			"style" => "width: 50px"
		],
		"fio" => [
			"label" => "Фамилия И.О",
			"style" => "30%"
		],
		"sending_date" => [
			"label" => "Время анализа",
			"style" => "30%"
		],
		"card_number" => [
			"label" => "Номер карты",
			"style" => "width: 25%"
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
		"direction-show-icon" => [
			"icon" => "glyphicon glyphicon-list",
			"label" => "Открыть направление"
		]
	];

	public $textNoData = "На этот день нет направлений";
	public $orderBy = "id desc";
	public $pageLimit = 25;

	public function init() {
		$this->provider = LDirection::model()->getSampleRepeatTableProvider();
		if (empty($this->criteria)) {
			$this->criteria = new CDbCriteria();
		}
		$this->criteria->addColumnCondition([
			"status" => LDirection::STATUS_TREATMENT_REPEAT
		]);
	}

	public function getAttributes($attributes = null, $excepts = []) {
		return parent::getAttributes($attributes, array_merge([
			"directionDates",
			"header",
			"textNoData",
			"controls",
		], $excepts));
	}
}