<?php

class Analyzer extends GActiveRecord {

	public function getForm() {
		return new AnalyzerForm();
	}

	public function rules() {
		return $this->getForm()->backward();
	}

	public function listTabs($list = null) {
		if ($list != null) {
			$items = [
				"list" => $list + [
						"data-tab" => UniqueGenerator::generate("tab"),
					]
			];
		} else {
			$items = [];
		}
		foreach (Analyzer::model()->findAll() as $analyzer) {
			$directions = ActiveRecord::getIds(AnalyzerType::model()->findDirections($analyzer->{"analyzer_type_id"}));
			$items[$analyzer->{"id"}] = [
				"label" => $analyzer->{"name"},
				"data-tab" => UniqueGenerator::generate("tab"),
				"data-id" => $analyzer->{"id"},
				"data-type" => $analyzer->{"analyzer_type_id"},
				"data-directions" => htmlspecialchars(json_encode($directions))
			];
		}
		return $items;
	}

	public function tableName() {
		return "lis.analyzer";
	}
}