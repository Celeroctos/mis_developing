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

<div class="row">
	<div class="col-xs-12">
		<div class="col-xs-12 text-center">
			<b>Медицинская карта</b>
			<br><br>
			<? $this->widget("AutoForm", [
				"model" => new LMedcardForm("treatment.edit")
			]) ?>
		</div>
		<hr>
		<div class="col-xs-12 text-center">
			<b>Пациент</b>
			<br><br>
			<? $this->widget("AutoForm", [
				"model" => new LPatientForm("treatment.edit")
			]) ?>
		</div>
	</div>
</div>

<!--<div class="row">-->
<!--	<div class="col-xs-12">-->
<!--		<div class="col-xs-6 text-center">-->
<!--			<b>ОМС</b>-->
<!--			<hr>-->
<!--			--><?// $this->widget("Form", [
//				"model" => new LPolicyForm("treatment.policy")
//			]) ?>
<!--		</div>-->
<!--		<div class="col-xs-6 text-center">-->
<!--			<b>Сведения о работе</b>-->
<!--			<hr>-->
<!--			--><?// $this->widget("Form", [
//				"model" => new LMedcardForm2("treatment.show")
//			]) ?>
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<!--<br>-->
<!--<div class="row">-->
<!--	<div class="col-xs-12 text-center">-->
<!--		<b>Сведения о работе</b>-->
<!--		<hr>-->
<!--		<div class="col-xs-6 col-xs-offset-3 text-center">-->
<!--			--><?// $this->widget("Form", [
//				"model" => new LPolicyForm("treatment.patient")
//			]) ?>
<!--		</div>-->
<!--	</div>-->
<!--</div>-->

<? $this->endWidget() ?>