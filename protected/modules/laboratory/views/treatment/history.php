<?php
/**
 * @var $this LaboratoryController
 */

$statuses = [
    Laboratory_Direction::STATUS_TREATMENT_ROOM => 'Процедурный кабинет',
];

$direction = UniqueGenerator::generate('tab');
$medcard = UniqueGenerator::generate('tab');

$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_DirectionCreator');
$this->widget('Laboratory_Modal_AboutMedcard');
$this->widget('Laboratory_Modal_PatientEditor');

$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_GREEN_JUSTIFIED,
	'items' => [
        'history-medcard-button' => [
            'label' => 'Медицинские карты',
            'data-tab' => $medcard
        ],
		'history-direction-button' => [
			'label' => 'Направления',
			'data-tab' => $direction
		],
	],
	'active' => 'history-medcard-button'
]) ?>

<hr>
<div id="<?= $medcard ?>">
    <?php $this->widget('Panel', [
        'title' => 'Медицинские карты лаборатории',
        'body' => $this->createWidget('Laboratory_Widget_MedcardSearchEx'),
        'id' => 'treatment-laboratory-medcard-table-panel',
        'collapsible' => false
    ]) ?>
</div>
<div id="<?= $direction ?>" style="display: none;">
    <?php $this->widget('Laboratory_Widget_DirectionTabs') ?>
    <hr>
</div>