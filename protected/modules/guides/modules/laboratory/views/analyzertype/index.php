<?php
/**
 * @var $this AnalysistypeController
 * @var $model GActiveRecord
 */
$this->widget("LaboratoryTabMenu");

ob_start();

print CHtml::tag("h4", [
	"class" => "text-center"
], "<hr>Типы анализов, исполняемые на этом типе анализатора<hr>");

$this->widget("AutoForm", [
	"model" => new FormModelAdapter("", [
		"analysis_type_id" => [
			"label" => "Типы анализов",
			"type" => "Multiple",
			"table" => [
				"name" => "lis.analysis_type",
				"format" => "%{name} (%{short_name})",
				"key" => "id",
				"value" => "name, short_name"
			]
		]
	]),
	"id" => "analysis-parameter-form"
]);

$this->widget("GGridView", [
	"title" => "Типы анализаторов",
	"url" => "guides/laboratory/analyzertype",
	"content" => ob_get_clean(),
	"model" => $model
]);