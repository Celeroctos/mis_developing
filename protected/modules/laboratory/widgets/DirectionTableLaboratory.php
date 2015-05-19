<?php

class DirectionTableLaboratory extends Table {

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
		"sender_id" => [
			"label" => "Направитель",
			"style" => "width: 15%"
		]
	];

	public $controls = [
		"direction-send-icon" => [
			"icon" => "glyphicon glyphicon-arrow-right",
			"label" => "Отправить на анализатор"
		]
	];

	public $tableClass = "table table-bordered";
	public $id = "laboratory-direction-table";
	public $textNoData = "На этот день нет направлений";
	public $orderBy = "id desc";
	public $pageLimit = -1;
	public $analyzerType = null;
	public $menuWidth = "50px";
	public $controlMode = ControlMenu::MODE_MENU;

	public function init() {
		$this->provider = LDirection::model()->getLaboratoryTableProvider();
		$this->controls = [
			"direction-repeat-icon" => [
				"icon" => "glyphicon glyphicon-repeat",
				"label" => "Отправить на повторный забор образца"
			],
		] + $this->controls;
		if (empty($this->criteria)) {
			$this->criteria = new CDbCriteria();
		}
		$this->criteria->addColumnCondition([
			"status" => LDirection::STATUS_LABORATORY
		]);
		if ($this->analyzerType !== null) {
			$types = AnalyzerType::model()->findAnalysisTypes($this->analyzerType);
			if (count($types = ActiveRecord::getIds($types)) > 0) {
				$this->criteria->addCondition("analysis_type_id in (".implode(",", $types).")");
			}
		}
	}

	public function getSerializedAttributes($attributes = null, $excepts = []) {
		return parent::getSerializedAttributes($attributes, array_merge([
			"directionDates",
			"header",
			"textNoData",
		], $excepts));
	}
}