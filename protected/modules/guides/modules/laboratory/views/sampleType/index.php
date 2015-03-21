<?
/**
 * @var $this SampleTypeController
 * @var $model SampleType
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Типы и подтипы образцов",
	"model" => $model,
	"url" => "guides/laboratory/sampleType"
]);