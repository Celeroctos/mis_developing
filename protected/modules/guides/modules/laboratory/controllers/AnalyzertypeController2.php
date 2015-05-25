<?php

class AnalyzertypeController2 extends GController {

	public function getModel() {
		return new AnalyzerType();
	}

	public function afterCreateOrUpdate(&$model) {
		if (isset($_POST["FormModelAdapter"])) {
			$post = Yii::app()->getRequest()->getPost("FormModelAdapter");
			foreach ($post as $key => &$row) {
				if (is_string($row)) {
					$row = json_decode($row);
				}
			}
		} else {
			$post = [
				"analysis_type_id" => [],
			];
		}
		if (!isset($post["analysis_type_id"])) {
			$post["analysis_type_id"] = [];
		}
		/** @var AnalyzerType $table */
		$table = $model;
		if (!isset($model->{"id"}) || empty($model->{"id"})) {
			throw new CException("Model's identification can't be null after save");
		}
		$parameters = ActiveRecord::getIds($table->findAnalysisTypes($model->{"id"}));
		$save = [];
		foreach ($post["analysis_type_id"] as $id) {
			if (!self::arrayInDrop([ "id" => $id ], $parameters)) {
				$save[] = $id;
			}
		}
		$table->saveAnalysisTypes($model->{"id"}, $save);
		$table->dropAnalysisTypes($model->{"id"}, $parameters);
	}

	public function afterLoad(&$model) {
		$parameters = AnalyzerType::model()->findAnalysisTypes($model["id"]);
		$model += [
			"analysis_type_id" => json_encode(ActiveRecord::getIds($parameters)),
		];
	}

	public function after($action, &$model, $form) {
		if ($action == "create" || $action == "update") {
			$this->afterCreateOrUpdate($model);
		} else if ($action == "load") {
			$this->afterLoad($model);
		}
	}
}