<?php

class Laboratory_Modal_PatientCreator extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Медицинская карта № ' . CHtml::tag('span', [
                    'id' => 'card_number'
                ], ''),
            'body' => $this->getWidget('Laboratory_Widget_PatientCreator'),
            'buttons' => [
                'save-button' => [
                    'text' => 'Сохранить',
                    'type' => 'button',
                    'class' => 'btn btn-primary'
                ],
                'copy-button' => [
                    'text' => 'Копировать',
                    'class' => 'btn btn-default',
                    'type' => 'button',
                    'align' => 'left'
                ],
                'insert-button' => [
                    'text' => 'Вставить',
                    'class' => 'btn btn-default',
                    'type' => 'button',
                    'align' => 'left'
                ],
                'clear-button' => [
                    'text' => 'Очистить',
                    'class' => 'btn btn-warning',
                    'type' => 'button',
                    'align' => 'left'
                ],
                'mis-find-button' => [
                    'text' => '<span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Пациент МИС',
                    'class' => 'btn btn-success',
                    'type' => 'button',
                    'attributes' => [
                        'data-toggle' => 'modal',
                        'data-target' => '#laboratory-modal-medcard-search'
                    ],
                    'align' => 'center'
                ]
            ],
            'class' => 'modal-90',
            'id' => 'laboratory-modal-patient-creator',
        ]);
    }
}