<?php
/**
 * @var AboutMedcard $this
 * @var int $age
 * @var mixed $medcard
 * @var mixed $patient
 * @var mixed $address
 * @var mixed $registerAddress
 */
?>

<div class="medcard-viewer">
<?php $this->beginWidget("Panel", [
	"title" => "Реквизитная информация",
	"controls" => [
		"panel-edit-button" => [
			"icon" => "glyphicon glyphicon-pencil",
			"class" => "btn btn-default btn-xs",
			"label" => "Редактировать",
			"onclick" => "",
		],
	],
	"collapsible" => true
]); ?>
<span class="medcard-info">
	<b>ФИО:&nbsp;</b> <?= $patient->surname." ".$patient->name." ".$patient->patronymic ?>&nbsp;<br>
	<b>Возраст:&nbsp;</b> <?= $age ?>&nbsp;<br>
	<b>Номер абмулаторной карты</b> <?= $medcard->card_number ?><br>
	<b>Адрес:&nbsp;</b> <?= $address->string ?><br>
	<b>Телефон:&nbsp;</b> <?= $patient->contact ?><br>
	<b>Место работы:&nbsp;</b> <?= $patient->work_place ?>
</span>
<?php $this->endWidget(); ?>
<?php $this->widget("Panel", [
	"title" => "Направления",
	"id" => "treatment-direction-history-panel",
	"body" => $this->createWidget("DirectionHistory", [
		"medcard" => $this->medcard
	]),
	"controls" => [
		"panel-update-button" => [
			"icon" => "glyphicon glyphicon-refresh",
			"class" => "btn btn-default btn-xs",
			"label" => "Обновить",
			"onclick" => "$(this).panel('update')"
		]
	],
	"collapsible" => true
]); ?>
</div>