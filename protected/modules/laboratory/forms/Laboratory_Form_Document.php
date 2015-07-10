<?php

class Laboratory_Form_Document extends FormModel {

	public $id;
	public $series;
	public $number;
	public $subdivision_name;
	public $issue_date;
	public $subdivision_code;
	public $type;

	public function backward() {
		return [
			[ 'id', 'hide', 'on' => 'treatment' ]
		];
	}

	public function config() {
		return [
			'id' => [
				'label' => 'Идентификатор',
				'type' => 'hidden'
			],
			'series' => [
				'label' => 'Серия',
				'type' => 'number',
				'rules' => 'required'
			],
			'number' => [
				'label' => 'Номер',
				'type' => 'number',
				'rules' => 'required'
			],
			'subdivision_name' => [
				'label' => 'Подразделение',
				'type' => 'text',
				'rules' => 'required'
			],
			'issue_date' => [
				'label' => 'Дата выдачи',
				'type' => 'date',
				'rules' => 'required'
			],
			'subdivision_code' => [
				'label' => 'Код подразделения',
				'type' => 'number',
				'rules' => 'required'
			],
            'type' => [
                'label' => 'Тип документа',
                'type' => 'dropdown',
                'table' => [
                    'name' => 'mis.doctypes',
                    'key' => 'id',
                    'value' => 'name',
                ],
            ],
		];
	}
}