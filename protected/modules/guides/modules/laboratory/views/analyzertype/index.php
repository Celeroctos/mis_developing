<?
/**
 * @var $this AnalysisTypeController
 * @var $model GActiveRecord
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Типы анализаторов",
	"url" => "guides/laboratory/analyzerType",
	"model" => $model
]);