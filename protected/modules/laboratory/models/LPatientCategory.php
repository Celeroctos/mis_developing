<?php

class LPatientCategory extends ActiveRecord {

	public $id;
	public $pregnant;
	public $smokes;
	public $gestational_age;
	public $menstruation_cycle;
	public $race;

	public function tableName() {
		return "lis.patient_category";
	}
}