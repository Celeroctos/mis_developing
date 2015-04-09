<?php
/**
 * @var MedcardEditableViewer $this - Self widget instance
 * @var string $number - Generated card number
 */
?>

<input id="laboratory-medcard-number" type="hidden" value="<?= $number ?>" data-cleanup="false">
<div class="col-xs-6 text-center">
	<b>Медицинская карта</b>
	<br><br>
	<?php $this->widget("AutoForm", [
		"model" => new LMedcardForm("treatment.edit", [
			"card_number" => $number
		])
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
		"model" => new LDirectionForm("treatment.edit")
	]) ?>
</div>