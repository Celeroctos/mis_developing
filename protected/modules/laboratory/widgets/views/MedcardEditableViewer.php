<?php

/**
 * @var MedcardEditableViewer $this - Self widget instance
 * @var string $number - Generated card number
 */

$this->beginWidget("CActiveForm", [
	"id" => "medcard-editable-viewer-form",
	"enableClientValidation" => true,
	"enableAjaxValidation" => true,
	"action" => Yii::app()->getBaseUrl() . "/laboratory/medcard/register",
	"htmlOptions" => [
		"class" => "form-horizontal col-xs-12",
		"role" => 'form'
	]
]); ?>

<div class="col-xs-6 text-center">
	<b>Медицинская карта</b>
	<br><br>
	<?php $this->widget("AutoForm", [
		"model" => new LMedcardForm("treatment.edit")
	]) ?>
	<hr>
	<b>Пациент</b>
	<br><br>
	<?php $this->widget("AutoForm", [
		"model" => new LPatientForm("treatment.edit")
	]) ?>
</div>
<div class="col-xs-6 text-center">
	<b>Направление</b>
	<br><br>
	<?php $this->widget("AutoForm", [
		"model" => new LMedcardForm("treatment.edit")
	]) ?>
</div>

<?php $this->endWidget() ?>