<?php

class Laboratory_Modal_PatientEditor extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Редактирование пациента лаборатории',
            'buttons' => [
                'laboratory-modal-patient-editor-save-button' => [
                    'text' => 'Сохранить',
                    'class' => 'btn btn-primary',
                    'type' => 'button',
                ],
            ],
            'id' => 'laboratory-modal-patient-editor',
        ]);
    }
}