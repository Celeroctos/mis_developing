<?php

class TreatmentController extends ControllerEx {

	public function actionView() {
		$this->render("view");
	}

	public function getModel() {
		return null;
	}
}