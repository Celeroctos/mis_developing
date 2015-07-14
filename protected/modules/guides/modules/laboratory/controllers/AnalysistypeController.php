<?php

class AnalysisTypeController extends GController {

	public function getModel() {
		return new AnalysisType();
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
				"analysis_parameter_id" => [],
				"sample_type_id" => []
			];
		}
		if (!isset($post["analysis_parameter_id"])) {
			$post["analysis_parameter_id"] = [];
		}
		if (!isset($post["sample_type_id"])) {
			$post["sample_type_id"] = [];
		}
		/** @var AnalysisType $table */
		$table = $model;
		if (!isset($model->{"id"}) || empty($model->{"id"})) {
			throw new CException("Model's identification can't be null after save");
		}
		$parameters = ActiveRecord::getIds($table->findAnalysisParameters($model->{"id"}));
		$save = [];
		foreach ($post["analysis_parameter_id"] as $id) {
			if (!self::arrayInDrop([ "id" => $id ], $parameters)) {
				$save[] = $id;
			}
		}
		$table->saveAnalysisParameters($model->{"id"}, $save);
		$table->dropAnalysisParameters($model->{"id"}, $parameters);
		/** @var AnalysisType $table */
		$table = $model;
		$parameters = ActiveRecord::getIds($table->findSampleTypes($model->{"id"}));
		$save = [];
		foreach ($post["sample_type_id"] as $id) {
			if (!self::arrayInDrop([ "id" => $id ], $parameters)) {
				$save[] = $id;
			}
		}
		$table->saveSampleTypes($model->{"id"}, $save);
		$table->dropSampleTypes($model->{"id"}, $parameters);
	}

	public function afterLoad(&$model) {
		$parameters = AnalysisType::model()->findAnalysisParameters($model["id"]);
		$samples = AnalysisType::model()->findSampleTypes($model["id"]);
		$model += [
			"analysis_parameter_id" => json_encode(ActiveRecord::getIds($parameters)),
			"sample_type_id" => json_encode(ActiveRecord::getIds($samples))
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
