<?php

class Laboratory_Form_Medcard extends FormModel {

	public $id;
	public $mis_medcard;
	public $sender_id;
	public $patient_id;
	public $card_number;
	public $enterprise_id;

	public function backward() {
		return [
			// hide identification number and reference to patient on medcard edit from treatment room
			[ [ 'id', 'patient_id' ], 'hide', 'on' => 'treatment' ],
		];
	}

	public function init() {
		$this->sender_id = Yii::app()->{'user'}->{'getState'}('doctorId');
	}

	public function config() {
		return [
			'id' => [
				'label' => 'Идентификатор',
				'type' => 'hidden',
				'rules' => 'numerical'
			],
			'card_number' => [
				'label' => 'Номер карты',
				'type' => 'text',
				'options' => [
					'data-cleanup' => 'false',
					'readonly' => 'true',
				],
				'rules' => 'required'
			],
			'mis_medcard' => [
				'label' => 'Номер карты в МИС',
				'type' => 'text',
				'options' => [
					'readonly' => 'true'
				],
				'rules' => 'safe'
			],
			'sender_id' => [
				'label' => 'Врач направитель',
				'type' => 'hidden',
				'options' => [
					'data-cleanup' => 'false'
				],
				'rules' => 'required'
			],
			'patient_id' => [
				'label' => 'Идентификатор пациента',
				'type' => 'number'
			],
			'enterprise_id' => [
				'label' => 'Подразделение',
				'type' => 'DropDown',
				'table' => [
					'name' => 'lis.enterprise',
					'key' => 'id',
					'value' => 'shortname'
				],
				'rules' => 'required'
			]
		];
	}
}