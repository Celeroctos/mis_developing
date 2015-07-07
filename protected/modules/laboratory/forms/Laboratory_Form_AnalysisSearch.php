<?php

class Laboratory_Form_AnalysisSearch extends FormModel {

	public $begin_date;
	public $end_date;

    public function config() {
        return [
            'begin_date' => [
                'label' => 'С...',
                'type' => 'date'
            ],
            'end_date' => [
                'label' => 'По...',
                'type' => 'date'
            ]
        ];
    }

    public function rules() {
        return [
            [ 'begin_date, end_date', 'safe' ],
        ];
    }

    public function attributeLabels() {
        return [
            'begin_date' => 'C...',
            'end_date' => 'По...',
        ];
    }
}