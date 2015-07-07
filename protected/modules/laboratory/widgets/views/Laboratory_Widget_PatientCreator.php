<?php
/**
 * @var Laboratory_Widget_PatientCreator $this - Self widget instance
 * @var string $number - Generated card number
 */
?>

<form name="PropertyForm">
<input id="laboratory-medcard-number" type="hidden" data-cleanup="false">
<div class="row">
	<div class="col-xs-6 text-center">
		<?php $this->widget("Panel", [
			"title" => "Медицинская карта",
			"body" => $this->createWidget("AutoForm", [
				"model" => new Laboratory_Form_Medcard("treatment", [
					"card_number" => $number
				])
			]),
			"headingClass" => "panel-heading panel-heading-narrow row no-margin",
			"collapsible" => true,
			"controlMode" => ControlMenu::MODE_NONE
		]) ?>
		<?php $this->widget("Panel", [
			"title" => "Пациент",
			"body" => $this->createWidget("AutoForm", [
				"model" => new Laboratory_Form_Patient("treatment")
			]),
			"headingClass" => "panel-heading panel-heading-narrow row no-margin",
			"collapsible" => true,
			"controlMode" => ControlMenu::MODE_NONE
		]) ?>
		<?php $this->widget("CheckPanel", [
			"title" => "Паспорт",
			"body" => $this->createWidget("AutoForm", [
				"model" => new Laboratory_Form_Passport("treatment")
			]),
			"name" => "PropertyForm[passport]",
			"headingClass" => "panel-heading panel-heading-narrow row no-margin",
			"controlMode" => ControlMenu::MODE_NONE,
			"checked" => false
		]) ?>
		<?php $this->widget("CheckPanel", [
			"title" => "Страховой полис",
			"body" => $this->createWidget("AutoForm", [
				"model" => new Laboratory_Form_Policy("treatment")
			]),
			"name" => "PropertyForm[policy]",
			"headingClass" => "panel-heading panel-heading-narrow row no-margin",
			"controlMode" => ControlMenu::MODE_NONE,
			"checked" => false
		]) ?>
	</div>
	<div class="col-xs-6 text-center">
		<?php $this->widget("Panel", [
			"title" => "Направление",
			"body" => $this->createWidget("Laboratory_Widget_DirectionCreator", [
				"disableControls" => true
			]),
			"headingClass" => "panel-heading panel-heading-narrow row no-margin",
			"collapsible" => true,
			"controlMode" => ControlMenu::MODE_NONE
		]) ?>
	</div>
</div>
</form>