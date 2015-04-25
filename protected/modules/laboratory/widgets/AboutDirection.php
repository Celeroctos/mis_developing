<?php

class AboutDirection extends Widget {

	public $direction = null;

	public function run() {
		if (empty($this->direction)) {
			throw new CException("Can't resolve empty direction identification value");
		} else if (!$direction = LDirection::model()->findByPk($this->direction)) {
			throw new CException("Can't resolve direction identification value \"$this->direction\"");
		}
		$this->render("AboutDirection", [
			"direction" => $direction
		]);
	}
}