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

<br>
<div id="<?= $direction ?>">
	<?php $this->widget('GridTable', [
		'provider' => new DirectionGridProvider()
	]) ?>
</div>
<div id="<?= $medcard ?>">
	<?php $this->widget('GridTable', [
		'provider' => new MedcardGridProvider()
	]) ?>
</div>