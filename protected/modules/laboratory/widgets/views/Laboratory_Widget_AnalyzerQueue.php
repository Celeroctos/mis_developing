<?php
/**
 * @var $this Laboratory_Widget_AnalyzerQueue
 */
?>
<?php $this->widget('Panel', [
    'title' => $this->getWidget('ControlMenu', [
        'controls' => [
            'analyzer-queue-start-button' => [
                'label' => 'Начать',
                'icon' => 'glyphicon glyphicon-play',
                'class' => 'btn btn-default'
            ],
            'analyzer-queue-stop-button' => [
                'label' => 'Остановить',
                'icon' => 'glyphicon glyphicon-stop',
                'class' => 'btn btn-default'
            ],
        ],
        'mode' => ControlMenu::MODE_BUTTON
    ]),
    'body' => CHtml::tag('h3', [
            'class' => 'text-center'
        ], 'Пусто') . CHtml::tag('ul', [
            'class' => 'nav nav-pills nav-stacked analyzer-queue-container'
        ], ''),
    'controls' => [
        'analyzer-queue-clear-button' => [
            'label' => 'Очистить',
            'icon' => 'glyphicon glyphicon-refresh',
            'class' => 'btn btn-warning'
        ],
    ],
    'controlMode' => ControlMenu::MODE_BUTTON,
    'titleWrapperClass' => 'col-xs-6 text-left no-padding',
    'controlsWrapperClass' => 'col-xs-6 text-right no-padding',
    'contentClass' => 'col-xs-12 no-padding no-margin panel-content',
    'id' => 'analyzer-task-viewer',
    'footer' => CHtml::tag('div', [
        'class' => 'progress'
    ], CHtml::tag('div', [
        'class' => 'progress-bar progress-bar-striped active',
        'role' => 'progressbar',
        'style' => 'width: 0;'
    ], CHtml::tag('span', [
        'class' => 'cr-only'
    ], '')))
]) ?>