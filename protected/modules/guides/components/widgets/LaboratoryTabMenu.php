<?php

class LaboratoryTabMenu extends CWidget {

	public $list = [
		"analysisSampleType" => [
			"label" => "Типы образцов для анализов",
			"privilege" => "guideEditAnalysisSample"
		],
		"analysisParam" => [
			"label" => "Параметры анализов",
			"privilege" => "guideEditAnalysisParam"
		],
		"analysisType" => [
			"label" => "Типы анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"analysisTypeParam" => [
			"label" => "Параметры для типов анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"analysisTypeSample" => [
			"label" => "Образцы для типов анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"analyzerType" => [
			"label" => "Типы анализаторов",
			"privilege" => "guideEditAnalyzerType"
		],
		"analyzerTypeAnalysis" => [
			"label" => "Анализы для типов анализаторов",
			"privilege" => "guideEditAnalyzerTypeAnalysis"
		],
		"machine" => [
			"label" => "Анализаторы",
			"privilege" => "guideEditAnalyzerType"
		]
	];

	/**
	 * Run widget
	 */
    public function run() {
        $controller = strtolower($this->controller->getId());
		print CHtml::openTag("ul", [
			"class" => "nav nav-tabs default-margin-bottom"
		]);
		print CHtml::tag("h4", [], "Справочники лаборатории");
		foreach ($this->list as $key => $config) {
			$key = strtolower($key);
			if (Yii::app()->user->checkAccess($config["privilege"])) {
				print CHtml::tag("li", [ "class" => ($controller == $key ? "active" : null) ],
					CHtml::link($config["label"], ["/guides/laboratory/$key"])
				);
			}
		}
		print CHtml::closeTag("ul");
    }
}