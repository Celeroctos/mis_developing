<?php

class Laboratory_Modal_PatientSearch extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Поиск медкарты в ЛИС',
            'body' => CHtml::tag('div', [
                'style' => 'padding: 10px'
            ], $this->getWidget('Laboratory_Widget_MedcardSearchEx', [
                'config' => [
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                    'emptyData' => 1,
                ]
            ])),
            'class' => 'modal-90',
            'id' => 'laboratory-modal-patient-search',
        ]);
    }
}