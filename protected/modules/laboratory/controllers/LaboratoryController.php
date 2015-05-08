<?php

class LaboratoryController extends Controller2 {

	public function actionView() {
		return $this->render("view", [
			"ready" => LDirection::model()->getCountOf(LDirection::STATUS_READY)
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