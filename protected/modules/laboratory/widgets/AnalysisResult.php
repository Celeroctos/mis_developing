<?php

class AnalysisResult extends Widget {

	/**
	 * @var int direction's identification number
	 */
	public $direction = null;

	public function run() {
		if (!$this->direction || !$this->direction = LDirection::model()->findByPk($this->direction)) {
			throw new CException("Unresolved direction's identification number ({$this->direction})");
		}
		return $this->render("AnalysisResult", [
			"direction" => $this->direction
		]);
	}
}