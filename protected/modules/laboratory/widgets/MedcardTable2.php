<?php

class MedcardTable2 extends MedcardTable {

	public function init() {
		$this->provider = LMedcard2::model()->getMedcardSearchTableProvider();
	}
} 