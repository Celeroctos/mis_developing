<?php
/**
 * @var $this Laboratory_Widget_AboutDirection - Instance of widget associated with that view file
 * @var $direction Laboratory_Direction - Row with information about direction
 */
?>
<?= CHtml::openTag('div', [
    'class' => 'about-direction col-xs-12 no-padding'
]) ?>
<div class="col-xs-6">
    <?php $this->widget('Laboratory_Widget_AboutMedcard', [
        'medcard' => $direction->{'medcard_id'}
    ]) ?>
</div>
<div class="col-xs-6">
    <?php $this->widget('Panel', [
        'title' => 'Информация об образце',
        'id' => 'treatment-about-direction-analysis-panel',
        'body' => $this->createWidget('Laboratory_Widget_DirectionEditor', [
            'direction' => $direction,
        ]),
        'controlMode' => ControlMenu::MODE_BUTTON_SM,
        'collapsible' => true,
    ]) ?>
</div>
<?= CHtml::closeTag('div') ?>