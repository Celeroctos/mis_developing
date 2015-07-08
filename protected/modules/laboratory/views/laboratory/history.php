<?php
/**
 * @var $this LaboratoryController
 */

$direction = UniqueGenerator::generate('tab');
$medcard = UniqueGenerator::generate('tab');

$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_DirectionCreator');
$this->widget('Laboratory_Modal_AboutMedcard');

$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_GREEN_JUSTIFIED,
	'items' => [
		'history-direction-button' => [
			'label' => 'Направления',
			'data-tab' => $direction
		],
		'history-medcard-button' => [
			'label' => 'Медицинские карты',
			'data-tab' => $medcard
		]
	],
	'active' => 'history-direction-button'
]) ?>

<hr>
<div id="<?= $direction ?>">
    <?php $this->widget('Panel', [
        'title' => 'Направления лаборатории',
        'body' => $this->createWidget('GridTable', [
            'provider' => new Laboratory_Grid_Direction()
        ]),
        'bodyClass' => 'panel-body no-padding',
    ]) ?>
</div>
<div id="<?= $medcard ?>">
    <?php $this->widget('Panel', [
        'title' => 'Медицинские карты лаборатории',
        'body' => $this->createWidget('GridTable', [
            'provider' => new Laboratory_Grid_Medcard()
        ]),
        'bodyClass' => 'panel-body no-padding',
    ]) ?>
</div>