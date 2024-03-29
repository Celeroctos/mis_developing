<?php

class Laboratory_Grid_Direction extends GridProvider {

	/**
	 * @var int status of direction to display
	 */
	public $status = null;

	/**
	 * @var string with date, for which directions
	 * 	should be displayed
	 */
	public $date = null;

	public $columns = [
		'id' => '#',
		'surname' => [
			'label' => 'Фамилия',
			'relation' => 'medcard.patient',
		],
		'name' => [
			'label' => 'Имя',
			'relation' => 'medcard.patient',
		],
		'patronymic' => [
			'label' => 'Отчество',
			'relation' => 'medcard.patient',
		],
        'mis_medcard' => [
            'label' => 'ЭМК',
            'relation' => 'medcard',
        ],
		'card_number' => [
			'label' => 'ЛКП',
			'relation' => 'medcard',
		],
		'enterprise_id' => [
			'label' => 'Направитель',
			'format' => '%{shortname}',
			'relation' => 'medcard.enterprise',
		],
		'analysis_type_id' => [
			'label' => 'Тип анализа',
			'format' => '%{name}',
			'relation' => 'analysis_type',
		]
	];

	public $menu = [
		'controls' => [
			'direction-show-icon' => [
				'icon' => 'glyphicon glyphicon-list',
				'label' => 'Открыть направление'
			],
		],
		'mode' => ControlMenu::MODE_ICON
	];

	public function search() {
        if ($this->date === true) {
            $this->date = date('Y-m-d');
        }
		$criteria = new CDbCriteria([
			'with' => [
				'medcard',
				'analysis_type',
				'medcard',
				'medcard.enterprise',
				'medcard.patient',
			]
		]);
		if ($this->status != null) {
			$criteria->addColumnCondition([
				'status' => $this->status
			]);
		}
		if ($this->date != null) {
			$criteria->addColumnCondition([
				'cast(sending_date as date)' => $this->date
			]);
		}
		return [
			'criteria' => $criteria,
			'sort' => [
				'attributes' => [
					'id',
					'patient.surname',
					'patient.name',
					'patient.patronymic',
					'medcard.card_number',
					'medcard.enterprise_id',
					'analysis_type_id',
				],
				'defaultOrder' => [
					'id' => CSort::SORT_DESC
				]
			],
			'pagination' => [
				'pageSize' => 50
			],
			'extra' => [
				'data-dates' => Laboratory_Direction::model()->getDates($this->status)
			],
			'textNoData' => $this->date != null ? 'На этот день нет направлений' : 'Нет направлений',
			'primaryKey' => 'id',
		];
	}

	public function model() {
		return new Laboratory_Direction();
	}
}