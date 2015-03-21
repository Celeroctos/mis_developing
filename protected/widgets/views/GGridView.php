<?php
/**
 * @var GGridView $this
 * @var array $columns
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
	"class" => "btn btn-primary"
], "Добавить");

$this->widget("zii.widgets.grid.CGridView", array(
	"ajaxUpdate" => false,
	"ajaxType" => "POST",
	"dataProvider" => $this->model->search(),
	"itemsCssClass" => "table table-bordered",
	"pager" => [
		"class" => "CLinkPager",
		"selectedPageCssClass" => "active",
		"header" => "",
		"htmlOptions" => [
			"class" => "pagination",
		]
	],
	"htmlOptions" => [
		"data-create-action" => Yii::app()->createUrl($this->url.$this->actions["create"]),
		"data-update-action" => Yii::app()->createUrl($this->url.$this->actions["update"]),
		"data-load-action" => Yii::app()->createUrl($this->url.$this->actions["load"]),
		"data-delete-action" => Yii::app()->createUrl($this->url.$this->actions["delete"])
	],
	"rowHtmlOptionsExpression" => 'array("data-id"=>$data->id)',
	"columns" => array_merge($columns, [[
			"class" => "CButtonColumn",
			"buttons" => [
				"headerHtmlOptions" => [
					"class" => "col-md-1",
				],
				"update" => [
					"imageUrl" => false,
					"options" => [
						"class" => "btn btn-default btn-block btn-xs update-button",
						"style" => "padding: 2px"
					],
					"url" => ""
				],
				"delete" => [
					"imageUrl" => false,
					"options" => [
						"class" => "btn btn-danger btn-block btn-xs delete-button confirm-delete",
					],
					"url" => ""
				]
			],
			"template" => "{update} {delete}",
			"deleteConfirmation" => false,
		]])
));