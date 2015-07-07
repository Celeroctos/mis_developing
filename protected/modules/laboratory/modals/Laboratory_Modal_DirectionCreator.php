<?php

class Laboratory_Modal_DirectionCreator extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Регистрация направления',
            'body' => $this->createWidget('Laboratory_Widget_DirectionCreator', [
                'id' => 'register-direction-form',
                'disableControls' => true
            ]),
            'buttons' => [
                'treatment-laboratory-modal-direction-creator-save-button' => [
                    'text' => 'Сохранить',
                    'class' => 'btn btn-primary',
                    'type' => 'button',
                ],
            ],
            'id' => 'laboratory-modal-direction-creator'
        ]);
    }
}