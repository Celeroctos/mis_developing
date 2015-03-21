<?php

class DoctorController extends GController {

	public function getClefTable() {
		return [
			"table" => "lis.doctor_clef",
			"key" => "doctor_id"
		];
	}

	public function getModel() {
		return null;
	}
}