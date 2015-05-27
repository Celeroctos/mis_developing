<?php

class LPatient extends ActiveRecord {

	public function relations() {
		return [
			"medcard" => [ self::HAS_MANY, "LMedcard", "patient_id" ],
		];
	}

	public function tableName() {
        return "lis.patient";
    }
} 