<?php

class LMedcard extends ActiveRecord {

	public $id;
	public $mis_medcard;
	public $sender_id;
	public $patient_id;
	public $card_number;
	public $enterprise_id;

	public function relations() {
		return [
			"medcard" => [ self::BELONGS_TO, "Medcard", "mis_medcard" ],
			"patient" => [ self::BELONGS_TO, "LPatient", "patient_id" ],
			"sender" => [ self::BELONGS_TO, "Doctor", "sender_id" ],
			"enterprise" => [ self::BELONGS_TO, "Enterprise", "enterprise_id" ],
			"direction" => [ self::BELONGS_TO, "LDirection", "medcard_id" ],
		];
	}

	/**
	 * Get instance of default table provider for current table
	 * @return TableProvider - Default table provider
	 */
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

	/**
	 * @return string - Name of table
	 */
	public function tableName() {
		return "lis.medcard";
	}
} 