<?php

class DirectionTableReady extends DirectionTableTreatment {

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
		"sending_date" => [
			"label" => "Время анализа",
			"style" => "30%"
		],
		"sender_id" => [
			"label" => "Направитель",
			"style" => "width: 15%"
		],
		"analysis_type_id" => [
			"label" => "Тип анализа"
		]
	];

	public function init() {
		$this->provider = LDirection::model()->getJustCreatedTableProvider();
		if ($this->date == null) {
			$this->date = date("Y-m-d");
		}
		if (empty($this->criteria)) {
			$this->criteria = new CDbCriteria();
		}
		$this->criteria->addColumnCondition([
			"status" => LDirection::STATUS_READY,
			"cast(sending_date as date)" => $this->date
		]);
		$this->controls = [
				"direction-finalize-icon" => [
					"label" => "Проверить результаты",
					"icon" => "glyphicon glyphicon-eye-open"
				]
			] + $this->controls;
		$this->directionDates = LDirection::model()->getDates(LDirection::STATUS_READY);
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