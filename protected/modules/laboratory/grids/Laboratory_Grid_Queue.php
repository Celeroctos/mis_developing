<?php

class Laboratory_Grid_Queue extends Laboratory_Grid_Direction {

	public $status = Laboratory_Direction::STATUS_LABORATORY;

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
			'direction-send-icon' => [
				'icon' => 'glyphicon glyphicon-arrow-right',
				'label' => 'Отправить на анализатор'
			],
			'direction-repeat-icon' => [
				'icon' => 'glyphicon glyphicon-repeat',
				'label' => 'Отправить на повторный забор образца'
			],
		],
		'mode' => ControlMenu::MODE_MENU
	];

	public $tableClass = 'table core-table';
	public $id = 'laboratory-direction-table';

	public function search() {
		return [ 'textNoData' => 'Нет направлений', ] + parent::search();
	}
}