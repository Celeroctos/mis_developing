<?php
/**
 * @var $this AnalysisTypeController
 * @var $model GActiveRecord
 */
$this->widget("LaboratoryTabMenu");

ob_start();

print CHtml::tag("h4", [
	"class" => "text-center"
], "<hr>Параметры анализа<hr>");

$this->widget("AutoForm", [
	"model" => new FormModelAdapter("", [
		"analysis_parameter_id" => [
			"label" => "Параметры",
			"type" => "Multiple",
			"table" => [
				"name" => "lis.analysis_type_parameter",
				"format" => "%{name} (%{short_name})",
				"key" => "id",
				"value" => "name, short_name"
			]
		]
	]),
	"id" => "analysis-parameter-form"
]);

print CHtml::tag("h4", [
	"class" => "text-center"
], "<hr>Типы и подтипы образцов<hr>");

$this->widget("AutoForm", [
	"model" => new FormModelAdapter("", [
		"sample_type_id" => [
			"label" => "Типы образцов",
			"type" => "Multiple",
			"table" => [
				"name" => "lis.sample_type_tree",
				"format" => "%{path}",
				"key" => "id",
				"value" => "path"
			]
		]
	]),
	"id" => "sample-type-form"
]);

$this->widget("GGridView", [
	"url" => "guides/laboratory/analysisType",
	"title" => "Типы анализов",
	"content" => ob_get_clean(),
	"model" => $model,
]);