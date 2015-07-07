<?php

class Laboratory_Grid_Medcard extends GridProvider {

	public $columns = [
		'card_number' => 'Номер',
		'surname' => [
			'label' => 'Фамилия',
			'relation' => 'patient',
		],
		'name' => [
			'label' => 'Имя',
			'relation' => 'patient',
		],
		'patronymic' => [
			'label' => 'Отчество',
			'relation' => 'patient',
		],
		'enterprise_id' => [
			'label' => 'МУ направитель',
			'format' => '%{shortname}',
			'relation' => 'enterprise',
		],
		'birthday' => [
			'label' => 'Дата рождения',
			'format' => '%{birthday}',
			'relation' => 'patient',
		]
	];

	public $menu = [
		'controls' => [
			'direction-register-icon' => [
				'icon' => 'glyphicon glyphicon-plus',
				'label' => 'Создать направление'
			],
			'medcard-show-icon' => [
				'icon' => 'glyphicon glyphicon-list',
				'label' => 'Открыть / Изменить медкарту'
			]
		],
		'mode' => ControlMenu::MODE_ICON
	];

	public function search() {
		return [
			'criteria' => [
				'with' => [
					'patient', 'enterprise'
				]
			],
			'sort' => [
				'attributes' => [
					'card_number',
					'patient.surname',
					'patient.name',
					'patient.patronymic',
					'enterprise.shortname',
					'patient.birthday'
				],
				'defaultOrder' => [
					'card_number' => CSort::SORT_ASC
				],
			],
			'pagination' => [
				'pageSize' => 25
			]
		];
	}

	public function model() {
		return new Laboratory_Medcard();
	}
}