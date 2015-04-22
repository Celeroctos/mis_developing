<?php

class DirectionTable extends Table {

	/**
	 * @var string|null - Current date when direction
	 * 	has been registered
	 */
	public $date = null;

	/**
	 * @var array - Array with all dates where we have
	 * 	one or more directions
	 */
	public $directionDates = [];

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

	public function renderExtra() {
		print CHtml::renderAttributes([
			"data-dates" => json_encode($this->directionDates)
		]);
		parent::renderExtra();
	}

	public function init() {
		$this->provider = LDirection::model()->getTreatmentTableProvider();
		if ($this->date == null) {
			$this->date = date("Y-m-d");
		}
		if (empty($this->searchCriteria)) {
			$this->searchCriteria = "cast(registration_time as date) = '{$this->date}'";
		} else {
			$this->searchCriteria .= " and cast(registration_time as date) = '{$this->date}'";
		}
		$this->directionDates = LDirection::model()->getDates();
	}
}