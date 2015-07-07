<?php

class Laboratory_Widget_PatientCreator extends Widget {

	/**
	 * @var string - Number of medcard to load, if number null, then it will
	 *	load empty editable form with generated new medcard number
	 */
	public $number = null;

	/**
	 * Executes the widget. This method is called
	 * by {@link CBaseController::endWidget}.
	 */
	public function run() {
		if ($this->number == null) {
			$number = LCardNumberGenerator::getGenerator()->generate();
		} else {
			$number = $this->number;
		}
		if ($this->number != null) {
			if (!($model = Laboratory_MedcardEx::model()->fetchInformation($this->number))) {
				throw new CException('Unresolved medcard number "'. $this->number .'"');
			}
		} else {
			$model = null;
		}
		$this->render(__CLASS__, [
			"number" => $number,
			"model" => $model
		]);
	}
}