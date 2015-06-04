<?php

class LMedcard extends ActiveRecord {

	public function relations() {
		return [
			"medcard" => [ self::BELONGS_TO, "Medcard", "mis_medcard", "joinType" => "LEFT OUTER JOIN" ],
			"patient" => [ self::BELONGS_TO, "LPatient", "patient_id", "joinType" => "INNER JOIN" ],
			"sender" => [ self::BELONGS_TO, "Doctor", "sender_id", "joinType" => "LEFT OUTER JOIN" ],
			"enterprise" => [ self::BELONGS_TO, "Enterprise", "enterprise_id", "joinType" => "LEFT OUTER JOIN" ],
			"direction" => [ self::BELONGS_TO, "LDirection", "medcard_id", "joinType" => "INNER JOIN" ],
		];
	}

	public function tableName() {
		return "lis.medcard";
	}
} 