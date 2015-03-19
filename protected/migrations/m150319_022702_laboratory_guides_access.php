<?php

class m150319_022702_laboratory_guides_access extends CDbMigration {

    public $actions = [
        "guideEditAnalysisType" => "Может редактировать типы анализов",
        "guideEditAnalysisParam" => "Может редактировать параметры анализов",
        "guideEditAnalysisTypeTemplate" => "Может редактировать шаблоны анализов",
        "guideEditAnalyzerType" => "Может редактировать типы анализаторов",
        "guideEditAnalyzerTypeAnalysis" => "Может редактировать списки типов анлизов для анализаторов",
        "guideEditAnalysisSample" => "Может редактировать типы образцов"
    ];

    public function safeUp() {
        foreach ($this->actions as $k => $v) {
            $this->execute("INSERT INTO mis.access_actions (\"name\", \"group\", \"accessKey\") VALUES ('$v', 1, '$k')");
        }
    }

    public function safeDown() {
        foreach ($this->actions as $k => $v) {
            $this->execute("DELETE FROM mis.access_actions WHERE \"accessKey\" = '$k'");
        }
    }
}