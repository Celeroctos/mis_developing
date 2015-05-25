<?php

class LaboratoryTabMenu extends CWidget {

	public $list = [
		"analysistype" => [
			"label" => "Типы анализов",
			"privilege" => "guideEditAnalysisType"
		],
		"analysisParameter" => [
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
		"analyzer" => [
			"label" => "Анализаторы",
			"privilege" => "guideEditAnalyzerType"
		],
		"doctor" => [
			"label" => "Врачи лаборатории",
			"privilege" => "guideEditAnalyzerType",
			"clef" => [
				"name" => "mis.doctors",
				"format" => "%{name}",
				"key" => "id",
				"value" => "(last_name || ' ' || first_name) as name",
				"clef" => [
					"table" => "lis.doctor_clef",
					"key" => "doctor_id"
				],
				"order" => "name"
			]
		],
		"enterprise" => [
			"label" => "Медицинские учреждения",
			"privilege" => "guideEditAnalyzerType",
			"clef" => [
				"name" => "mis.enterprise_params",
				"key" => "id",
				"value" => "shortname",
				"clef" => [
					"table" => "lis.enterprise_clef",
					"key" => "enterprise_id"
				],
				"order" => "shortname"
			]
		]
	];

	/**
	 * Run widget
	 */
    public function run() {
        $controller = strtolower($this->controller->getId());
		# Fix for bootstrap tab double border. Hot! Hot! Hot!
		print CHtml::tag("style", [], <<< CSS
.nav-tabs > li > a {
	  border: none;
}
CSS
);
		foreach ($this->list as $key => &$config) {
			if (!isset($config["clef"])) {
				continue;
			}
			if (!isset($config["clef"]["id"])) {
				$config["clef"]["id"] = UniqueGenerator::generate("clef");
			}
			$values = Yii::app()->getDb()->createCommand()
				->select("*")
				->from("{$config["clef"]["clef"]["table"]}")
				->queryAll();
			$values = ActiveRecord::getIds($values, $config["clef"]["clef"]["key"]);
			$this->widget("Modal", [
				"title" => "Редактировать связи \"{$config["label"]}\"",
				"body" => $this->widget("AutoForm", [
					"model" => new FormModelAdapter("", [
						"keys" => [
							"label" => $config["label"],
							"type" => "multiple",
							"value" => json_encode($values),
							"options" => [
								"data-cleanup" => "false"
							],
							"table" => $config["clef"],
						]
					]),
					"id" => $config["clef"]["id"]
				], true),
				"buttons" => [
					"clef-save-button" => [
						"text" => "Сохранить",
						"class" => "btn btn-primary",
						"type" => "button",
						"attributes" => [
							"data-url" => Yii::app()->createUrl("/guides/laboratory/$key/clef")
						]
					]
				],
				"id" => $config["clef"]["id"]."-modal"
			]);
		}
		print CHtml::openTag("ul", [
			"class" => "nav nav-tabs default-margin-bottom"
		]);
		print CHtml::tag("h4", [], "Справочники лаборатории");
		foreach ($this->list as $key => &$config) {
			if (!Yii::app()->user->checkAccess($config["privilege"])) {
				continue;
			}
			if (isset($config["clef"])) {
				print CHtml::tag("li", [ "class" => ($controller == $key ? "active" : null) ],
					CHtml::link($config["label"]."&nbsp;".CHtml::tag("span", [
							"class" => "glyphicon glyphicon-link"
						], ""), "#", [
						"data-toggle" => "modal",
						"data-target" => "#{$config["clef"]["id"]}-modal"
					])
				);
			} else {
				print CHtml::tag("li", [ "class" => ($controller == strtolower($key) ? "active" : null) ],
					CHtml::link($config["label"], ["/guides/laboratory/$key"])
				);
			}
		}
		print CHtml::closeTag("ul");
    }
}