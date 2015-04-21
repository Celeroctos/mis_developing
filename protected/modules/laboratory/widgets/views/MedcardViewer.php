<?php
/**
 * @var MedcardViewer $this
 * @var int $age
 * @var mixed $medcard
 * @var mixed $patient
 * @var mixed $address
 * @var mixed $registerAddress
 */

CHtml::openTag("div", [
	"class" => "medcard-viewer"
]); ?>

<?php $this->beginWidget("Panel", [
	"title" => "Реквизитная информация",
	"controls" => [
		"panel-edit-button" => [
			"class" => "btn btn-primary btn-xs",
			"label" => "<span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;Редактировать",
			"onclick" => "$(this).panel('update')",
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

<?php $this->beginWidget("Panel", [
	"title" => "Результаты обследования",
	"collapsible" => true
]);
print "Не реализовано";
$this->endWidget();

$this->beginWidget("Panel", [
	"title" => "Согласия пациента",
	"collapsible" => true
]);
print "Не реализовано";
$this->endWidget();

$this->beginWidget("Panel", [
	"title" => "История приемов",
	"collapsible" => true
]);
print "Не реализовано";
$this->endWidget();

$this->beginWidget("Panel", [
	"title" => "Печать документов",
	"collapsible" => true
]);
print "Не реализовано";
$this->endWidget();

$this->beginWidget("Panel", [
	"title" => "Направления",
	"collapsible" => true
]);
print "Не реализовано";
$this->endWidget();

CHtml::closeTag("div");