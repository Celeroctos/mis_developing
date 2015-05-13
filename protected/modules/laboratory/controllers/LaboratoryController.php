<?php

class LaboratoryController extends Controller2 {

	public function actionView() {
		$analyzers = Analyzer::model()->listTabs([
			"label" => "Все направления",
			"icon" => "glyphicon glyphicon-list",
			"active" => "true"
		]);
		if (count($analyzers) == 1) {
			$analyzers += [
				"empty" => [
					"label" => "Нет доступных анализаторов",
					"disabled" => "true"
				]
			];
		}
		return $this->render("view", [
			"analyzers" => $analyzers,
			"total" => LDirection::model()->getCountOf(LDirection::STATUS_LABORATORY),
			"ready" => LDirection::model()->getCountOf(LDirection::STATUS_READY),
		]);
	}

	/**
	 * Override that method to return controller's model
	 * @return ActiveRecord - Controller's model instance
	 */
	public function getModel() {
		return null;
	}
}