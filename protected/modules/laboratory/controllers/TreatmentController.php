<?php

class TreatmentController extends Controller2 {

	/**
	 * Default view action
	 */
	public function actionView() {
		$this->render("view", [
			"directionRepeats" => LDirection::model()->getCountOfRepeats()
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