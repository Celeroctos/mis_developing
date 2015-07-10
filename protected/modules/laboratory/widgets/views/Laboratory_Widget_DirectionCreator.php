<?php
/**
 * @var $this Laboratory_Widget_DirectionCreator
 */
print CHtml::openTag("div", [
	"class" => "direction-creator-wrapper"
]);
$this->widget("AutoForm", [
	"model" => new Laboratory_Form_DirectionEx(),
	"url" => Yii::app()->getUrlManager()->createUrl($this->url),
	"defaults" => $this->defaults,
	"id" => $this->id
]);
if (!$this->disableControls) {
	print "<hr>";
	print CHtml::tag("button", [
		"class" => "btn btn-default direction-creator-cancel",
		"type" => "button",
		"style" => "margin-right: 10px",
	], "Отменить");
	print CHtml::tag("button", [
		"class" => "btn btn-primary direction-creator-register",
		"type" => "button"
	], "Сохранить");
}
print CHtml::closeTag("div");