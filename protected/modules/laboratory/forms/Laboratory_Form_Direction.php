<?php

class Laboratory_Form_Direction extends FormModel {

	public $id;
	public $barcode;
	public $status;
	public $comment;
	public $analysis_type_id;
	public $medcard_id;
	public $sender_id;
	public $sending_date;
	public $treatment_room_employee_id;
	public $laboratory_employee_id;
	public $history;
	public $ward_id;
	public $enterprise_id;
	public $mis_medcard;

	public function backward() {
		return [
			$this->createFilter('treatment', [
				'comment',
				'analysis_type_id',
				'history',
				'ward_id'
			])
		];
	}

	public function config() {
		return [
			'id' => [
				'label' => 'Идентификатор',
				'type' => 'hidden',
				'rules' => 'numerical'
			],
			'barcode' => [
				'label' => 'Штрих-код',
				'type' => 'number',
				'options' => [
					'readonly' => 'true'
				],
				'rules' => 'required'
			],
            'status' => [
                'label' => 'Статус',
                'type' => 'DirectionStatus',
                'rules' => 'required'
            ],
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
			'comment' => [
				'label' => 'Комментарий',
				'options' => [
					'rows' => '5'
				],
				'type' => 'TextArea'
			],
			'medcard_id' => [
				'label' => 'Медкарта',
				'type' => 'number',
				'rules' => 'required',
				'options' => [
					'data-cleanup' => 'false'
				],
				'hidden' => 'true'
			],
			'sender_id' => [
				'label' => 'Врач направитель',
				'type' => 'dropdown',
				'table' => [
					'format' => '%{last_name} %{first_name}',
					'name' => 'mis.doctors',
					'key' => 'id',
					'value' => 'first_name, last_name',
					'order' => 'last_name, first_name'
				],
				'rules' => 'required',
				'hidden' => 'true'
			],
			'sending_date' => [
				'label' => 'Дата направления',
				'type' => 'date',
				'rules' => 'required'
			],
			'treatment_room_employee_id' => [
				'label' => 'Сотрудник процедурного кабинета',
				'type' => 'DropDown',
				'table' => [
					'format' => '%{last_name} %{first_name}',
					'name' => 'mis.doctors',
					'key' => 'id',
					'value' => 'first_name, last_name',
					'order' => 'last_name, first_name'
				],
				'rules' => 'required'
			],
			'laboratory_employee_id' => [
				'label' => 'Сотрудник лаборатории',
				'type' => 'DropDown',
				'rules' => 'required',
				'table' => [
					'format' => '%{last_name} %{first_name}',
					'name' => 'mis.doctors',
					'key' => 'id',
					'value' => 'first_name, last_name',
					'order' => 'last_name, first_name'
				]
			],
            'history' => [
                'label' => 'Медикаментозный анамнез',
				'options' => [
					'rows' => '5'
				],
                'type' => 'TextArea'
            ],
			'ward_id' => [
				'label' => 'Отдел',
				'type' => 'DropDown',
				'rules' => 'required',
                'table' => [
                    'name' => 'mis.wards',
                    'key' => 'id',
                    'value' => 'name'
                ]
			],
		];
	}
}