<?php

class MedcardTable2 extends MedcardTable {

	public $primaryKey = "card_number";
	public $orderBy = "card_number";

	/**
	 * Initialize medcard table for MIS
	 */
	public function init() {
		$this->provider = LMedcard2::model()->getMedcardSearchTableProvider();
	}
}