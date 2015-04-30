<?php

class DirectionTableTreatment extends Table {

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
			"style" => "width: 50px"
		],
		"fio" => [
			"label" => "Фамилия И.О",
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
	public $searchCriteria = "status = 1";
	public $pageLimit = 25;

	public function renderExtra() {
		print CHtml::renderAttributes([
			"data-dates" => json_encode($this->directionDates)
		]);
		parent::renderExtra();
	}

	public function init() {
		$this->provider = LDirection::model()->getJustCreatedTableProvider();
		if ($this->date == null) {
			$this->date = date("Y-m-d");
		}
		if (empty($this->searchCriteria)) {
			$this->searchCriteria = "cast(sending_date as date) = '{$this->date}'";
		} else {
			$this->searchCriteria .= " and cast(sending_date as date) = '{$this->date}'";
		}
		$this->directionDates = LDirection::model()->getDates(LDirection::STATUS_TREATMENT_ROOM);
	}

	public function getSerializedAttributes($attributes = null, $excepts = []) {
		return parent::getSerializedAttributes($attributes, array_merge([
				"directionDates",
				"header",
				"textNoData",
				"controls",
			], $excepts));
	}
}