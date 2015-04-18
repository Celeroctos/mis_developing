<?php

class MedcardTable2 extends MedcardTable {

	/**
	 * Initialize medcard table for MIS
	 */
	public function init() {
		$this->provider = LMedcard2::model()->getMedcardSearchTableProvider();
	}
}