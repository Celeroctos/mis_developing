<?php
/**
 * @var $this Laboratory_Widget_AnalyzerHelper
 * @var $analyzers array
 */
$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_PILLS,
    'id' => 'analyzer-tab-menu',
    'items' => $analyzers,
    'special' => 'analyzer-task-menu-item',
    'active' => array_values($analyzers)[0]['data-id']
]) ?>
<hr>
<div class="row no-margin direction-view-wrapper">
<div class="col-xs-6 no-padding">
    <?php $this->widget('Laboratory_Widget_DirectionPanel', [
        'title' => 'Все направления на анализ',
        'body' => $this->createWidget('GridTable', [
            'provider' => new Laboratory_Grid_Queue()
        ]),
        'panelClass' => 'panel panel-default no-select',
        'status' => Laboratory_Direction::STATUS_LABORATORY,
        'date' => false,
        'search' => false,
    ]) ?>
</div>
<div class="col-xs-6" style="padding-right: 0">
<?php foreach ($analyzers as $class => $analyzer): ?>
    <?php $first = !isset($first) ?>
    <div class="col-xs-12 no-padding laboratory-tab-container" id="<?= $analyzer['data-tab'] ?>" style="<?= $first ? 'display: block;' : 'display: none;' ?>">
        <?php $this->widget('Laboratory_Widget_AnalyzerQueue') ?>
        <hr>
        <button class="btn btn-default laboratory-analyzer-info-button btn-lg" data-toggle="modal" data-target="#laboratory-modal-queue-guide">
            <span class="glyphicon glyphicon-info-sign"></span>
            &nbsp;Помощь
        </button>
        <hr>
        <div class="laboratory-clock-wrapper"></div>
    </div>
<?php endforeach ?>
</div>
</div>
<span class="glyphicon glyphicon-arrow-right laboratory-tr-pointer" style="display: none;"></span>