<?php

class DirectionGridProvider extends GridProvider {

	/**
	 * @var int status of direction to display
	 */
	public $status = null;

	public $columns = [
		"id" => "#",
		"surname" => [
			"label" => "Фамилия",
			"relation" => "medcard.patient",
		],
		"name" => [
			"label" => "Имя",
			"relation" => "medcard.patient",
		],
		"patronymic" => [
			"label" => "Отчество",
			"relation" => "medcard.patient",
		],
		"card_number" => [
			"label" => "Номер",
			"relation" => "medcard",
		],
		"enterprise_id" => [
			"label" => "Направитель",
			"format" => "%{shortname}",
			"relation" => "medcard.enterprise",
		],
		"analysis_type_id" => [
			"label" => "Тип анализа",
			"format" => "%{name}",
			"relation" => "analysis_type",
		]
	];

	public function search() {
		$criteria = new CDbCriteria([
			"with" => [
				"medcard",
				"analysis_type",
				"medcard",
				"medcard.enterprise",
				"medcard.patient",
			]
		]);
		if ($this->status != null) {
			$criteria->addColumnCondition([
				"status" => $this->status
			]);
		}
		return [
			"criteria" => $criteria,
			"sort" => [
				"attributes" => [
					"id",
					"patient.surname",
					"patient.name",
					"patient.patronymic",
					"medcard.card_number",
					"medcard.enterprise_id",
					"analysis_type_id",
				],
				"defaultOrder" => [
					"id" => CSort::SORT_DESC
				]
			],
			"textNoData" => "На этот день нет направлений",
			"primaryKey" => "id"
		];
	}

	public function model() {
		return new LDirection();
	}
}