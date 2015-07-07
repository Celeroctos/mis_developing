<?php

class Laboratory_Widget_DirectionHistory extends Widget {

	/**
	 * @var int - Medcard identification number (primary key) from
	 *	database's table (lis/medcard/id)
	 */
	public $medcard = null;

	/**
	 * Run widget
	 */
	public function run() {
		if (empty($this->medcard)) {
			throw new CException('Can\'t resolve laboratory medcard with empty identification number');
		} else if (!LMedcard::model()->findByPk($this->medcard)) {
			throw new CException('Can\'t resolve laboratory medcard with "'. $this->medcard .'" identification number');
		}
		$this->render(__CLASS__, [
			'directions' => LDirection::model()->getWithAnalysis('medcard_id = :medcard_id', [
				':medcard_id' => $this->medcard
			])
		]);
	}
}