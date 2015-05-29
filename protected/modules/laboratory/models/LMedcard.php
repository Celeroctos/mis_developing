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

	public function getMedcardSearchTableProvider() {
		return new TableProvider($this, $this->getDbConnection()->createCommand()
			->selectDistinct("
				m.id as medcard_id,
                m.card_number,
                p.sex as phone,
                concat(p.surname, ' ', p.name, ' ', p.patronymic) as fio,
                p.birthday as birthday,
                e.shortname as enterprise,
                cast(a.registration_time as date) as registration_date")
			->from("lis.medcard as m")
			->join("lis.patient as p", "p.id = m.patient_id")
			->leftJoin("lis.direction as d", "d.medcard_id = m.id")
			->leftJoin("lis.analysis as a", "a.direction_id = d.id")
			->leftJoin("mis.enterprise_params as e", "e.id = m.enterprise_id")
		);
	}

	public function tableName() {
		return "lis.medcard";
	}
} 