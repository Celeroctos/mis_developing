<?php

class Laboratory_Modal_MedcardSearch extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Поиск медкарты в МИС',
            'body' => CHtml::tag('div', [
                'style' => 'padding: 10px'
            ], $this->getWidget('Laboratory_Widget_MedcardSearch', [
                'provider' => 'MedcardExGridProvider',
                'config' => [
                    'emptyData' => 1
                ]
            ])),
            'buttons' => [
                'load' => [
                    'text' => 'Открыть',
                    'class' => 'btn btn-primary',
                    'attributes' => [
                        'data-loading-text' => 'Загрузка ...'
                    ],
                    'type' => 'button'
                ]
            ],
            'class' => 'modal-90',
            'id' => 'laboratory-modal-medcard-search',
        ]);
    }
}