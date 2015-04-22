<?php
/**
 * @var MedcardEditableViewer $this - Self widget instance
 * @var string $number - Generated card number
 */
?>

<input id="laboratory-medcard-number" type="hidden" value="<?= $number ?>" data-cleanup="false">
<div class="row">
	<div class="col-xs-6 text-center">
		<b>Медицинская карта</b>
		<br><br>
		<?php $this->widget("AutoForm", [
			"model" => new LMedcardForm("treatment", [
				"card_number" => $number
			])
		]) ?>
		<hr>
		<b>Пациент</b>
		<br><br>
		<?php $this->widget("AutoForm", [
			"model" => new LPatientForm("treatment")
		]) ?>
	</div>
	<div class="col-xs-6 text-center">
		<b>Направление</b>
		<br><br>
		<?php $this->widget("AutoForm", [
			"model" => new LDirectionForm("treatment")
		]) ?>
	</div>
</div>
<div class="col-xs-12 text-center">
	<hr>
	<form class="col-xs-12" name="PropertyForm">
		<div class="btn-group" id="treatment-document-control-wrapper" data-toggle="buttons">
			<label class="btn btn-default" data-target="#treatment-direction-passport-form">
				<input type="checkbox" name="PropertyForm[passport]" autocomplete="off"><b>Паспорт&nbsp;</b>
				<span class="glyphicon glyphicon-chevron-down"></span>
			</label>
			<label class="btn btn-default" data-target="#treatment-direction-policy-form">
				<input type="checkbox" name="PropertyForm[policy]" autocomplete="off"><b>Полис&nbsp;</b>
				<span class="glyphicon glyphicon-chevron-down"></span>
			</label>
		</div>
	</form>
	<hr>
	<div class="col-xs-6">
		<div id="treatment-direction-passport-form">
			<b>Паспорт</b>
			<br><br>
			<?php $this->widget("AutoForm", [
				"model" => new LPassportForm("treatment")
			]) ?>
		</div>
	</div>
	<div class="col-xs-6">
		<div id="treatment-direction-policy-form">
			<b>Полис</b>
			<br><br>
			<?php $this->widget("AutoForm", [
				"model" => new LPolicyForm("treatment")
			]) ?>
		</div>
	</div>
</div>