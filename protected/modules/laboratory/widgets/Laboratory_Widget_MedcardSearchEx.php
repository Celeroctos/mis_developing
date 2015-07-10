<?php

class Laboratory_Widget_MedcardSearchEx extends Laboratory_Widget_MedcardSearch {

	public $id = "treatment-medcard-table";

	public function init() {
        $this->widget('Laboratory_Modal_DirectionCreator');
        $this->widget('Laboratory_Modal_AboutMedcard');
		parent::init();
	}
}