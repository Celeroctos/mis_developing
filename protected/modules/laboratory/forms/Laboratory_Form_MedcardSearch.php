<?php

class Laboratory_Form_MedcardSearch extends FormModel {

    public $surname;
    public $name;
    public $patronymic;
	public $card_number;

    public function config() {
        return [
			'surname' => [
				'label' => 'Фамилия',
				'type' => 'text',
			],
			'name' => [
				'label' => 'Имя',
				'type' => 'text',
			],
			'patronymic' => [
				'label' => 'Отчество',
				'type' => 'text',
			],
            'card_number' => [
                'label' => 'Номер ЭМК/ЛКП',
                'type' => 'text',
            ],
        ];
    }
}