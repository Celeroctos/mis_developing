<?php

class MedcardEditableViewer extends Widget {

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
			$generator = new CardnumberGenerator();
			$rule = MedcardRule::model()->find("name = :name", [
				":name" => "Лаборатория"
			]);
			$generator->setPrevNumber("");
			if ($rule != null) {
				$number = $generator->generateNumber($rule["id"]);
			} else {
//				throw new CException("Can't resolve medcard rule for laboratory");
			}
		} else {
			$number = $this->number;
		}
		if ($this->number != null) {
			if (!($model = LMedcard2::model()->fetchInformation($this->number))) {
				throw new CException("Unresolved medcard number \"{$this->number}\"");
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