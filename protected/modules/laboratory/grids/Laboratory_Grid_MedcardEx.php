<?php

class Laboratory_Grid_MedcardEx extends GridProvider {

	public $columns = [
		'card_number' => 'Номер',
		'last_name' => [
			'label' => 'Фамилия',
			'relation' => 'policy',
		],
		'first_name' => [
			'label' => 'Имя',
			'relation' => 'policy',
		],
		'middle_name' => [
			'label' => 'Отчество',
			'relation' => 'policy',
		],
		'enterprise_id' => [
			'label' => 'МУ направитель',
			'format' => '%{shortname}',
			'relation' => 'enterprise',
		],
		'birthday' => [
			'label' => 'Дата рождения',
			'format' => '%{birthday}',
			'relation' => 'policy',
		],
		'contact' => 'Контакты'
	];

	public function search() {
		return [
			'criteria' => [
				'with' => [
					'policy', 'enterprise'
				],
			],
			'sort' => [
				'attributes' => [
					'card_number',
					'policy.last_name',
					'policy.first_name',
					'policy.middle_name',
					'enterprise_id',
					'policy.birthday',
					'contact'
				],
				'defaultOrder' => [
					'card_number' => CSort::SORT_ASC
				]
			],
			'primaryKey' => 'card_number'
		];
	}

	public function model() {
		return new Laboratory_MedcardEx();
	}
}