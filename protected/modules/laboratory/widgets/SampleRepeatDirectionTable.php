<?php

class SampleRepeatDirectionTable extends Table {

	public $header = [
		"id" => [
			"label" => "#",
			"style" => "width: 50px"
		],
		"fio" => [
			"label" => "Фамилия И.О",
			"style" => "30%"
		],
		"registration_time" => [
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
		],
		"direction-restore-icon" => [
			"icon" => "glyphicon glyphicon-arrow-left",
			"label" => "Отменить"
		]
	];

	public $textNoData = "На этот день нет направлений";
	public $orderBy = "id desc";
	public $searchCriteria = "status = 4";
	public $pageLimit = 25;

	public function init() {
		$this->provider = LDirection::model()->getSampleRepeatTableProvider();
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