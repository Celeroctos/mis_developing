<?php

class m150321_064622_laboratory_guides_access extends CDbMigration
{
	public $actions = [
		"guideEditAnalysisType" => "Может редактировать типы анализов",
		"guideEditAnalyzerType" => "Может редактировать типы анализаторов",
	];

	public function safeUp() {
		foreach ($this->actions as $k => $v) {
			$this->execute("INSERT INTO mis.access_actions (\"name\", \"group\", \"accessKey\") VALUES ('$v', 1, '$k')")
		}
	}

	public function safeDown() {
		foreach ($this->actions as $k => $v) {
			$this->execute("DELETE FROM mis.access_actions WHERE \"accessKey\" = '$k'")
		}
	}
}