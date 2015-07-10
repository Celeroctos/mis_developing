<?php

class Laboratory_Form_Policy extends FormModel {

	public $id;
	public $surname;
	public $name;
	public $patronymic;
	public $birthday;
	public $number;
	public $issue_date;
	public $insurance_id;
	public $document_type;

	public function backward() {
		return [
			$this->createFilter('treatment', [
				'number',
				'insurance_id',
				'issue_date',
			])
		];
	}
    
	public function config() {
		return [
			'id' => [
				'label' => 'Первичный ключ',
				'type' => 'hidden'
			],
			'surname' => [
				'label' => 'Фамилия',
				'type' => 'text',
				'rules' => 'required'
			],
			'name' => [
				'label' => 'Имя',
				'type' => 'text',
				'rules' => 'required'
			],
			'patronymic' => [
				'label' => 'Отчество',
				'type' => 'text'
			],
			'birthday' => [
				'label' => 'Дата рождения',
				'type' => 'date',
				'rules' => 'required'
			],
			'number' => [
				'label' => 'Номер полиса',
				'type' => 'text',
				'rules' => 'required'
			],
			'issue_date' => [
				'label' => 'Дата выдачи',
				'type' => 'date',
				'rules' => 'required'
			],
			'insurance_id' => [
				'label' => 'Страховая компания',
				'type' => 'DropDown',
				'table' => [
					'name' => 'mis.insurances',
					'key' => 'id',
					'value' => 'name'
				],
				'rules' => 'required'
			]
		];
	}
}