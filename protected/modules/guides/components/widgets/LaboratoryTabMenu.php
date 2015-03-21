<?php

class LaboratoryTabMenu extends CWidget {

	public $list = [
		"analysisType" => [
			"label" => "Типы анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"analysisTypeParameter" => [
			"label" => "Параметры анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"sampleType" => [
			"label" => "Типы и подтипы образцов",
			"privilege" => "guideEditAnalysisType"
		],
		"analyzerType" => [
			"label" => "Типы анализаторов",
			"privilege" => "guideEditAnalyzerType"
		],
//		"analyzer" => [
//			"label" => "Анализаторы",
//			"privilege" => "guideEditAnalyzerType"
//		]
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