<?php

class Laboratory_Form_AboutDirection extends FormModel {

	public $direction_id;
	public $medcard_id;
	public $sample_type_id;
	public $analysis_parameters;
	public $comment;
	public $sending_time;
	public $sending_date;

	public function rules() {
		return [
			[ 'direction_id, medcard_id, sample_type_id, analysis_parameters, sending_time, sending_date', 'RequiredValidator' ]
		];
	}

	public function attributeLabels() {
		return [
			'direction_id' => 'Идентификатор направления',
			'medcard_id' => 'идентификатор медкарты',
			'sample_type_id' => 'Тип образца',
			'analysis_parameters' => 'Параметры анализа',
			'sending_time' => 'Время направления',
			'sending_date' => 'Дата направления',
			'comment' => 'Комментарий'
		];
	}

	public function config() {
		return [
			'sample_type_id' => [
				'type' => 'dropdown'
			]
		];
	}
}