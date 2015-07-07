<?php

class Laboratory_Form_PatientCategory extends FormModel {

	public $id;
	public $pregnant;
	public $smokes;
	public $gestational_age;
	public $menstruation_cycle;
	public $race;

	public function config() {
		return [
			'id' => [
				'label' => 'Иднетификатор',
				'type' => 'hidden'
			],
			'pregnant' => [
				'label' => 'Беременна',
				'type' => 'YesNo',
				'rules' => 'required'
			],
			'smokes' => [
				'label' => 'Курит',
				'type' => 'YesNo',
				'rules' => 'required'
			],
			'gestational_age' => [
				'label' => 'Срок беременности',
				'type' => 'GestationalAge',
				'rules' => 'required'
			],
			'menstruation_cycle' => [
				'label' => 'Менструальный цикл',
				'type' => 'MenstruationCycle',
				'rules' => 'required'
			],
			'race' => [
				'label' => 'Расовая принадлежность',
				'type' => 'Race',
				'rules' => 'required'
			]
		];
	}
}