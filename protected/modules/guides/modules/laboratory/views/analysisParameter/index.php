<?php
/**
 * @var $this AnalysistypeController
 */
$this->widget("LaboratoryTabMenu");

$this->widget("GGridView", [
	"title" => "Параметры анализов",
	"url" => "guides/laboratory/analysisParameter",
	"model" => new AnalysisParameter()
]);