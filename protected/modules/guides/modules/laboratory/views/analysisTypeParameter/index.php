<?
/**
 * @var $this AnalysisTypeController
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Параметры анализов",
	"url" => "guides/laboratory/analysisTypeParameter",
	"model" => new AnalysisTypeParameter()
]);