<?php
/**
 * @var Laboratory_Widget_AboutMedcard $this
 * @var int $age
 * @var mixed $medcard
 * @var mixed $patient
 * @var mixed $address
 * @var mixed $registerAddress
 */
?>

<div class="medcard-viewer">
<?php $this->beginWidget('Panel', [
	'title' => 'Реквизитная информация',
	'controls' => [
		'panel-edit-button' => [
			'icon' => 'glyphicon glyphicon-pencil',
			'class' => 'btn btn-default btn-sm',
			'label' => 'Редактировать',
			'onclick' => '',
		],
	],
	'collapsible' => true
]); ?>
<span class="medcard-info">
	<b>Номер абмулаторной карты:&nbsp;</b> <?= $medcard->card_number ?><br>
	<b>Номер ЭМК:&nbsp;</b> <?= $medcard->mis_medcard ?><br>
	<b>ФИО:&nbsp;</b> <?= $patient->surname." ".$patient->name." ".$patient->patronymic ?>&nbsp;<br>
	<b>Возраст:&nbsp;</b> <?= $age ?>&nbsp;<br>
	<b>Адрес:&nbsp;</b> <?= $address->string ?><br>
	<b>Телефон:&nbsp;</b> <?= $patient->contact ?><br>
	<b>Место работы:&nbsp;</b> <?= $patient->work_place ?>
</span>
<?php $this->endWidget(); ?>
<?php $this->widget('Panel', [
	'title' => 'Направления',
	'id' => 'treatment-direction-history-panel',
	'body' => $this->createWidget('Laboratory_Widget_DirectionHistory', [
		'medcard' => $this->medcard
	]),
	'controls' => [
		'panel-update-button' => [
			'icon' => 'glyphicon glyphicon-refresh',
			'class' => 'btn btn-default btn-sm',
			'label' => 'Обновить',
			'onclick' => '$(this).panel("update")'
		]
	],
	'collapsible' => true
]); ?>
</div>