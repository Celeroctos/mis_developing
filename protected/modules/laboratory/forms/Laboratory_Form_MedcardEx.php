<?php

class Laboratory_Form_MedcardEx extends FormModel {

	public $privelege_code;
	public $snils;
	public $address;
	public $address_reg;
	public $doctype;
	public $serie;
	public $docnumber;
	public $who_gived;
	public $gived_date;
	public $contact;
	public $invalid_group;
	public $card_number;
	public $enterprise_id;
	public $policy_id;
	public $reg_date;
	public $work_place;
	public $work_address;
	public $post;
	public $profession;
	public $motion;
	public $address_str;
	public $address_reg_str;
	public $user_created;
	public $date_created;

	public function backward() {
		return [
			$this->createFilter('treatment.show', [
				'work_place',
				'work_address',
				'post',
				'profession',
				'snils',
				'invalid_group',
				'privelege_code',
				'contact'
			])
		];
	}

	public function config() {
		return [
			'work_place' => [
				'label' => 'Место работы',
				'type' => 'text'
			],
			'work_address' => [
				'label' => 'Адрес работы',
				'type' => 'text'
			],
			'post' => [
				'label' => 'Должность',
				'type' => 'text'
			],
			'profession' => [
				'label' => 'Профессия',
				'type' => 'text'
			],
			'snils' => [
				'label' => 'СНИЛС',
				'type' => 'text'
			],
			'invalid_group' => [
				'label' => 'Группа инвалидности',
				'type' => 'DisabilityGroup'
			],
			'privelege_code' => [
				'label' => 'Привилегия',
				'type' => 'DropDown',
				'table' => [
					'name' => 'mis.privileges',
					'key' => 'id',
					'value' => 'name'
				]
			],
			'contact' => [
				'label' => 'Телефон',
				'type' => 'text'
			],
		];
	}
}