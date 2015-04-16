<?php

class MedcardViewer extends Widget {

	/**
	 * @var string - Medcard number from laboratory module
	 * @see mis.medcards.card_number
	 */
	public $number;

	/**
	 * Run widget
	 * @throws CException
	 */
	public function run() {
		if (empty($this->number)) {
			throw new CException("Medcard viewer requires medcard number, see MedcardViewer::number");
		}
		if (($model = LMedcard2::model()->fetchByNumber($this->number)) == null) {
			throw new CException("Unresolved medcard number \"{$this->number}\"");
		}
		$model["age"] = DateTime::createFromFormat("Y-m-d", $model["birthday"])
			->diff(new DateTime())->y;
		foreach ($model as $key => &$value) {
			if (empty($value)) {
				$value = "Нет";
			}
		}
		$this->render(__CLASS__, [
			"model" => $model
		]);
	}
}