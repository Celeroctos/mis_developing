<?php
/**
 * @var $this GFastGridView
 * @var $columns array
 */

$this->widget("zii.widgets.grid.CGridView", [
	"ajaxType" => "GET",
	"dataProvider" => $this->model->search(),
	"ajaxUrl" => [$this->url],
//	"pager" => [
//		"class" => "CLinkPager",
//		"selectedPageCssClass" => "active",
//		"header" => "",
//		"htmlOptions" => [
//			"class" => "pagination",
//		]
//	],
	"htmlOptions" => [
		"data-create-action" => Yii::app()->createUrl($this->url."create"),
		"data-update-action" => Yii::app()->createUrl($this->url."update"),
		"data-load-action" => Yii::app()->createUrl($this->url."load"),
		"data-delete-action" => Yii::app()->createUrl($this->url."delete")
	],
	"rowHtmlOptionsExpression" => 'array("data-id"=>$data->id)',
	"columns" => array_merge($columns, [[
		"class" => "CButtonColumn",
		"buttons" => [
			"headerHtmlOptions" => [
				"class" => "col-xs-12",
			],
			"update" => [
				"imageUrl" => false,
				"options" => [
					"class" => "btn btn-default btn-xs update-button",
					"style" => "padding: 2px"
				],
				"url" => ""
			],
			"delete" => [
				"imageUrl" => false,
				"options" => [
					"class" => "btn btn-danger btn-xs delete-button confirm-delete"
				],
				"url" => ""
			]
		],
		"template" => "{update} {delete}",
		"deleteConfirmation" => false,
	]])
]);