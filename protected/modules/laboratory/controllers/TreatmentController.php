<?php

namespace laboratory\controllers;

use ControllerEx;

class TreatmentController extends ControllerEx {

	public function actionView() {
		$this->render("view");
	}

    public function actionHistory() {
        $this->render('history');
    }
}