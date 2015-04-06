<?php

class DoctorTable extends Table {

	public $header = [
		"id" => [
			"label" => "#",
		],
		"last_name" => [
			"label" => "Фамилия"
		],
		"first_name" => [
			"label" => "Имя"
		],
		"middle_name" => [
			"label" => "Отчество"
		]
	];

	public function init() {
		$this->provider = new TableProvider("Doctor");
	}
}