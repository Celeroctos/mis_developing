<?php

class Laboratory_Form_MedcardSearch extends FormModel {

	public $card_number;
	public $surname;
	public $name;
	public $patronymic;
	public $enterprise_id;
	public $phone;

    public function config() {
        return [
            'card_number' => [
                'label' => 'Номер ЛКП',
                'type' => 'text'
            ],
            'enterprise_id' => [
                'label' => 'Направитель',
                'type' => 'DropDown',
				'table' => [
					'name' => 'mis.enterprise_params',
					'key' => 'id',
					'value' => 'shortname'
				]
            ],
			'surname' => [
				'label' => 'Фамилия',
				'type' => 'text'
			],
			'name' => [
				'label' => 'Имя',
				'type' => 'text'
			],
			'patronymic' => [
				'label' => 'Отчество',
				'type' => 'text'
			],
            'phone' => [
                'label' => 'Телефон',
                'type' => 'Phone',
                'rules' => 'LPhoneValidator'
            ]
        ];
    }
}