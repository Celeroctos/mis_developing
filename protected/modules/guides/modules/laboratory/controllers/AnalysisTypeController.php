<?php

class AnalysisTypeController extends GController {

	public function getModel() {
		return new AnalysisType();
	}

	public function afterCreateOrUpdate(&$model) {
		if (isset($_POST["FormModelAdapter"])) {
			$post = Yii::app()->getRequest()->getPost("FormModelAdapter");
			if (is_string($post["analysis_parameter_id"])) {
				$post["analysis_parameter_id"] = json_decode($post["analysis_parameter_id"]);
			}
		} else {
			$post = [
				"analysis_parameter_id" => []
			];
		}
		/** @var AnalysisType $table */
		$table = $model;
		$parameters = ActiveRecord::getIds(
			$table->findAnalysisParameters($model->{"id"})
		);
		$save = [];
		foreach ($post["analysis_parameter_id"] as $id) {
			if (!self::arrayInDrop([ "id" => $id ], $parameters)) {
				$save[] = $id;
			}
		}
		$table->addAnalysisParameters($model->{"id"}, $save);
		$table->dropAnalysisParameters($model->{"id"}, $parameters);
	}

	public function afterLoad(&$model) {
		$parameters = AnalysisType::model()->findAnalysisParameters($model["id"]);
		$model["analysis_parameter_id"] = json_encode(ActiveRecord::getIds($parameters));
	}

	public function after($action, &$model, $form) {
		if ($action == "create" || $action == "update") {
			$this->afterCreateOrUpdate($model);
		} else if ($action == "load") {
			$this->afterLoad($model);
		}
	}
}
