<?php
/**
 * @var $this laboratory\controllers\LaboratoryController
 * @var $analyzers array
 */

$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_AnalysisResult');
$this->widget('Laboratory_Modal_QueueGuide');
$this->widget('Laboratory_Modal_DirectionCreator');
$this->widget('Laboratory_Modal_AboutMedcard');
$this->widget('Laboratory_Modal_PatientEditor');

$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_GREEN_JUSTIFIED,
    'items' => [
        'direction-analyzer' => [
            'label' => 'Направления и анализаторы',
            'data-tab' => 'laboratory-direction-grid-wrapper',
        ],
        'direction-ready' => [
            'label' => 'Выполненые результаты&nbsp;' . CHtml::tag('span', [
                    'class' => 'badge', 'id' => 'treatment-repeat-counts'
                ], Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_READY)),
            'data-tab' => 'laboratory-ready-grid-wrapper'
        ],
        'direction-closed' => [
            'label' => 'Готовые результаты&nbsp;' . CHtml::tag('span', [
                    'class' => 'badge', 'id' => 'treatment-repeat-counts'
                ], Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_CLOSED)),
            'data-tab' => 'laboratory-closed-grid-wrapper'
        ],
    ],
    'active' => 'direction-analyzer',
]) ?>

<script type="text/javascript" src="<?= Yii::app()->createUrl('js/laboratory/laboratory.js') ?>"></script>
<div class="laboratory-table-wrapper table-wrapper">
    <div id="laboratory-direction-grid-wrapper" class="col-xs-12 no-padding">
        <?php $this->widget('Laboratory_Widget_AnalyzerHelper') ?>
    </div>
    <div id="laboratory-ready-grid-wrapper" class="no-display text-left">
        <hr>
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Завершенные направления',
            'body' => $this->createWidget('GridTable', [
                'provider' => new Laboratory_Grid_Direction([
                    'status' => Laboratory_Direction::STATUS_READY,
                    'menu' => [
                        'controls' => [
                            'direction-result-icon' => [
                                'icon' => 'glyphicon glyphicon-eye-open',
                                'label' => 'Проверить результаты',
                            ],
                            'direction-show-icon' => [
                                'icon' => 'glyphicon glyphicon-list',
                                'label' => 'Открыть направление',
                            ],
                        ],
                        'mode' => ControlMenu::MODE_ICON,
                    ]
                ]),
            ]),
            'status' => Laboratory_Direction::STATUS_READY,
            'search' => false,
        ]) ?>
    </div>
    <div id="laboratory-closed-grid-wrapper" class="no-display text-left">
        <hr>
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Завершенные направления',
            'body' => $this->createWidget('GridTable', [
                'provider' => new Laboratory_Grid_Direction([
                    'status' => Laboratory_Direction::STATUS_CLOSED,
                    'menu' => [
                        'controls' => [
                            'direction-result-icon' => [
                                'icon' => 'glyphicon glyphicon-eye-open',
                                'label' => 'Проверить результаты',
                            ],
                            'direction-show-icon' => [
                                'icon' => 'glyphicon glyphicon-list',
                                'label' => 'Открыть направление',
                            ],
                        ],
                        'mode' => ControlMenu::MODE_ICON,
                    ]
                ]),
            ]),
            'status' => Laboratory_Direction::STATUS_CLOSED,
            'search' => false,
        ]) ?>
        <hr>
        <button class="btn btn-primary btn-lg">
            <span class="glyphicon glyphicon-print"></span>&nbsp;Печать
        </button>
    </div>
</div>