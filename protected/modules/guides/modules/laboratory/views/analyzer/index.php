<?
/**
 * @var $this AnalyzerController
 * @var $model GActiveRecord
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Анализаторы",
	"url" => "guides/laboratory/analyzer",
	"model" => $model
]);