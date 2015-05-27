<?php

class OmsGridProvider extends GridProvider {

	public $tableClass = "table core-table";
	public $id = "laboratory-direction-table";

	public $columns = [
		"id" => "#",
		"oms_number" => "#",
		"last_name" => "Фамилия",
		"first_name" => "Имя",
		"middle_name" => "Отчество",
	];

	public function search() {
		return [
			"criteria" => [
			],
			"sort" => [
				"attributes" => [
					"id",
					"oms_number",
					"last_name",
					"first_name",
					"middle_name",
				],
				"defaultOrder" => [
					"id" => CSort::SORT_DESC
				]
			],
			"primaryKey" => "id"
		];
	}

	public function model() {
		return new Oms();
	}
}