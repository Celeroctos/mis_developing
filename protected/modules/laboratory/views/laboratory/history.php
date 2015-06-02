<?php
/**
 * @var $this LaboratoryController
 */
$direction = UniqueGenerator::generate('tab');
$medcard = UniqueGenerator::generate('tab');
$this->widget('Modal', [
	'title' => 'Информация о направлении',
	'body' => CHtml::tag('h1', [
		'class' => 'text-center'
	], 'Направление не выбрано'),
	'id' => 'treatment-about-direction-modal'
]);
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
]);
$this->widget("Modal", [
	"title" => "Регистрация направления",
	"body" => $this->createWidget("DirectionCreator", [
		"id" => "register-direction-form",
		"disableControls" => true
	]),
	"buttons" => [
		"treatment-register-direction-modal-save-button" => [
			"text" => "Сохранить",
			"class" => "btn btn-primary",
			"type" => "button",
		],
	],
	"id" => "register-direction-modal"
]);
$this->widget("Modal", [
	"title" => "Медицинская карта",
	"body" => "<h4 class=\"text-center no-margin\">Медкарта не выбрана</h4>",
	"buttons" => [],
	"id" => "show-medcard-modal"
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