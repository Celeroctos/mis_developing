<?php

class Laboratory_Form_DirectionEx extends FormModel {

	public $analysis_type_id;
	public $analysis_parameters;
	public $pregnant;
	public $smokes;
	public $gestational_age;
	public $menstruation_cycle;
	public $race;
	public $history;
	public $comment;
	public $medcard_id;
	public $mis_medcard;

	public function config() {
		return [
			'analysis_type_id' => [
				'label' => 'Тип анализа',
				'type' => 'DropDown',
				'table' => [
					'name' => 'lis.analysis_type',
					'key' => 'id',
					'value' => 'name'
				],
				'rules' => 'required',
			],
			'analysis_parameters' => [
				'label' => 'Параметры анализа',
				'type' => 'hidden',
				'rules' => 'required'
			],
			'pregnant' => [
				'label' => 'Беременна',
				'type' => 'YesNo',
				'options' => [
					'data-cleanup' => 'false',
					'value' => '1',
				]
			],
			'smokes' => [
				'label' => 'Курит',
				'type' => 'YesNo'
			],
			'gestational_age' => [
				'label' => 'Срок беременности',
				'type' => 'GestationalAge'
			],
			'menstruation_cycle' => [
				'label' => 'Менструальный цикл',
				'type' => 'MenstruationCycle'
			],
			'race' => [
				'label' => 'Расовая принадлежность',
				'type' => 'Race',
				'rules' => 'required'
			],
			'history' => [
				'label' => 'Медикаментозный анамнез',
				'options' => [
					'rows' => '5'
				],
				'type' => 'TextArea'
			],
			'comment' => [
				'label' => 'Комментарий',
				'options' => [
					'rows' => '5'
				],
				'type' => 'TextArea'
			],
			'medcard_id' => [
				'label' => 'Номер медкарты',
				'type' => 'hidden'
			]
		];
	}
}