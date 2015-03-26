<?php
/**
 * @var GGridView $this
 */

$this->widget("Modal", [
	"title" => "Добавить \"{$this->title}\"",
	"body" => $this->getWidget("AutoForm", [
		"url" => Yii::app()->createUrl($this->url.$this->actions["create"]),
		"model" => $this->model->form
	]) . $this->content,
	"buttons" => [
		"register-button" => [
			"text" => "Добавить",
			"class" => "btn btn-primary",
			"type" => "button"
		]
	],
	"id" => "register-guide-modal"
]);

$this->widget("Modal", [
	"title" => "Редактировать \"{$this->title}\"",
	"body" => $this->getWidget("AutoForm", [
		"url" => Yii::app()->createUrl($this->url.$this->actions["update"]),
		"model" => $this->model->form
	]) . $this->content,
	"buttons" => [
		"update-button" => [
			"text" => "Сохранить",
			"class" => "btn btn-primary",
			"type" => "button"
		]
	],
	"id" => "update-guide-modal"
]);

if ($this->title != null) {
	print CHtml::tag("h4", [], $this->title);
}

print CHtml::tag("button", [
	"data-toggle" => "modal",
	"data-target" => "#register-guide-modal",
	"class" => "btn btn-primary",
	"style" => "margin-bottom: 20px"
], "Добавить");

CHtml::tag("div", [
	"id" => "grid-container"
], "");

$this->widget("GFastGridView", [
	"model" => $this->model,
	"url" => $this->url
]);