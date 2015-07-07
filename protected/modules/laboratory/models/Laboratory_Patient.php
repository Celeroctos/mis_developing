<?php

class Laboratory_Patient extends ActiveRecord {

	public function relations() {
		return [
			'medcard' => [ self::HAS_MANY, 'Laboratory_Medcard', 'patient_id', 'joinType' => 'INNER JOIN' ],
		];
	}

	public function tableName() {
        return 'lis.patient';
    }
} 