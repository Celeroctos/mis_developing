<?php

class Laboratory_Form_Patient extends FormModel {

	public $id;
	public $surname;
	public $name;
	public $patronymic;
	public $sex;
	public $birthday;
	public $passport_id;
	public $policy_id;
	public $register_address_id;
	public $address_id;
	public $contact;
	public $work_place;

	public function backward() {
		return [
            [ [ 'passport_id', 'policy_id', 'register_address_id', 'address_id' ], 'hide', 'on' => 'edit' ],
			[ 'id', 'hide', 'on' => 'treatment' ],
		];
	}
    
	public function config() {
		return [
			'id' => [
				'label' => 'Идентификатор',
				'type' => 'hidden',
				'rules' => 'numerical'
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
				'type' => 'text',
				'rules' => 'safe'
			],
			'sex' => [
				'label' => 'Пол',
				'type' => 'sex',
				'rules' => 'required'
			],
			'birthday' => [
				'label' => 'Дата рождения',
				'type' => 'date',
				'rules' => 'required'
			],
			'passport_id' => [
				'label' => 'Пасспорт',
				'type' => 'hidden'
			],
			'policy_id' => [
				'label' => 'Полис',
				'type' => 'hidden'
			],
			'register_address_id' => [
				'label' => 'Адрес регистрации',
				'type' => 'address',
				'rules' => 'safe',
				'table' => [
					'name' => 'lis.address',
					'format' => 'р. %{region_name}, район. %{district_name}, ул. %{street_name}, д. %{house_number}, кв. %{flat_number}',
					'key' => 'id',
					'value' => 'street_name, house_number, flat_number, region_name, district_name'
				]
			],
			'address_id' => [
				'label' => 'Адрес проживания',
				'type' => 'address'
			],
			'contact' => [
				'label' => 'Телефон',
				'type' => 'text'
			],
			'work_place' => [
				'label' => 'Место работы',
				'type' => 'text'
			]
		];
	}
}