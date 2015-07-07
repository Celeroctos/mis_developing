<?php

class Laboratory_Modal_AboutDirection extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Информация о направлении',
            'body' => CHtml::tag('h1', [
                'class' => 'text-center',
            ], 'Направление не выбрано'),
            'id' => 'laboratory-modal-about-direction',
        ]);
    }
}