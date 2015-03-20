<?php
/**
 * @var GGridView $this
 * @var array $columns
 */

/** @var Widget $widget */
$widget = $this->beginWidget("Modal", [
	"title" => "Добавить \"{$this->title}\"",
	"buttons" => [
		"register-button" => [
			"text" => "Добавить",
			"class" => "btn btn-primary",
			"type" => "submit"
		]
	]
]);

/** @var AutoForm $form */
$form = $this->widget("AutoForm", [
	"url" => Yii::app()->createUrl($this->url.$this->actions["create"]),
	"model" => $this->model->getForm()
]);

$this->endWidget("Modal");

if ($this->title != null) {
	print CHtml::tag("h4", [], $this->title);
}

print CHtml::tag("button", [
	"class" => "btn btn-primary",
	"data-toggle" => "modal",
	"data-target" => "#".$widget->id
], "Добавить");

$this->widget("zii.widgets.grid.CGridView", array(
	"id" => "analysis-param-grid",
	"ajaxUpdate" => true,
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
	"rowHtmlOptionsExpression" => 'array("data-id"=>$data->id)',
	"columns" => array_merge($columns, [[
			"class" => "CButtonColumn",
			"buttons" => [
				'headerHtmlOptions' => [
					'class' => 'col-md-1',
				],
				'update' => [
					'imageUrl' => false,
					'options' => [
						'class' => 'btn btn-default btn-block btn-xs update-button',
						'style' => 'padding: 2px'
					],
					'url' => ''
				],
				'delete' => [
					'imageUrl' => false,
					'options' => [
						'class' => 'btn btn-danger btn-block btn-xs delete-button',
					],
					'url' => ''
				]
			],
			"template" => "{update} {delete}"
		]])
)); ?>
<script>
	$(document).ready(function() {
		$("#<?= $widget->id ?> #register-button").click(function() {
			var form = $(this).parents(".modal").find("form");
			$.post(form.attr("action"), form.serialize(), function(json) {
				if (!json["status"]) {
					return Laboratory.postFormErrors(form, json)
				} else if (json["message"]) {
					Laboratory.createMessage({
						message: json["message"],
						sign: "ok",
						type: "success"
					});
					form.parents(".modal").modal("hide");
				}
			}, "json");
		});
		$(".grid-view .update-button").click(function() {
			var id = $(this).parents("tr[data-id]").data("id");
		});
		$(".grid-view .delete-button").click(function() {
			$.post("<?= Yii::app()->createUrl($this->url.$this->actions["delete"]) ?>", {
				id: $(this).parents("tr[data-id]").data("id")
			}, function(json) {
				if (!json["status"]) {
					Laboratory.createMessage({
						message: json["message"]
					});
				} else if (json["message"]) {
					Laboratory.createMessage({
						message: json["message"],
						sign: "ok",
						type: "success"
					});
				}
				console.log(json);
			}, "json");
		});
	});
</script>