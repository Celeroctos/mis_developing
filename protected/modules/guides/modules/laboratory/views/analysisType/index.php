<?
/**
 * @var $this AnalysisTypeController
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Типы анализов",
	"url" => "guides/laboratory/analysistype",
	"model" => new AnalysisType()
]);