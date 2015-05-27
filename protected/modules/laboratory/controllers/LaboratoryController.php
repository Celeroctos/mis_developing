<?php

class LaboratoryController extends ControllerEx {

	public function actionView() {
		return $this->render("view", [
			"analyzers" => $this->getAnalyzerTabs(),
			"total" => LDirection::model()->getCountOf(LDirection::STATUS_LABORATORY),
			"ready" => LDirection::model()->getCountOf(LDirection::STATUS_READY),
		]);
	}

	public function actionTabs() {
		try {
			$analyzers = $this->getAnalyzerTabs();
			$result = [];
			foreach ($analyzers as $i => $analyzer) {
				$result[] = [
					"id" => $analyzer["data-id"],
					"directions" => $analyzer["data-directions"]
				];
			}
			$this->leave([
				"result" => $result
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function getAnalyzerTabs() {
		$analyzers = Analyzer::model()->listTabs();
		if (count($analyzers) != 0) {
			return $analyzers;
		};
		return $analyzers + [
			"empty" => [
				"label" => "Нет доступных анализаторов",
				"disabled" => "true"
			]
		];
	}

	/**
	 * Override that method to return controller's model
	 * @return ActiveRecord - Controller's model instance
	 */
	public function getModel() {
		return null;
	}
}