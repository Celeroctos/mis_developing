<?php

class Laboratory_Modal_AnalysisResult extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Результаты анализа',
            'body' => CHtml::tag('h1', [
                'class' => 'text-center'
            ], 'Направление не выбрано'),
            'buttons' => [
                'submit-analysis-result-button' => [
                    'text' => 'Подтвердить',
                    'class' => 'btn btn-primary',
                    'type' => 'button',
                ]
            ],
            'class' => 'modal-lg',
            'id' => 'laboratory-modal-analysis-result'
        ]);
    }
}