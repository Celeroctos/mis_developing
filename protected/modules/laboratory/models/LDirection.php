<?php

class LDirection extends ActiveRecord {

	const STATUS_TREATMENT_ROOM = 1;
	const STATUS_LABORATORY = 2;
	const STATUS_READY = 3;
	const STATUS_TREATMENT_REPEAT = 4;

	public function getWithAnalysis($where = "", $params = []) {
		return $this->getDbConnection()->createCommand()
			->select("d.*, at.short_name as analysis_type_short_name")
			->from("lis.direction as d")
			->join("lis.analysis_type as at", "d.analysis_type_id = at.id")
			->where($where, $params)
			->queryAll();
	}

	public function getAnalysisParameters($id, $unchecked = true) {
		$checked = $this->getDbConnection()->createCommand()
			->select("ap.*")
			->from("lis.direction_parameter as dp")
			->join("lis.direction as d", "d.id = dp.direction_id")
			->join("lis.analysis_parameters AS ap", "ap.id = dp.analysis_type_parameter_id")
			->where("d.analysis_type_id = ap.analysis_type_id")
			->andWhere("dp.direction_id = :direction_id", [
				":direction_id" => $id
			])->queryAll();
		foreach ($checked as &$row) {
			$row["checked"] = 1;
		}
		if ($unchecked === false) {
			return $checked;
		}
		$except = [];
		foreach ($checked as &$row) {
			$except[] = $row["id"];
			$row["checked"] = 1;
		}
		$query = $this->getDbConnection()->createCommand()
			->select("ap.*")
			->from("lis.analysis_parameters as ap")
			->join("lis.analysis_type as at", "at.id = ap.analysis_type_id")
			->join("lis.direction as d", "at.id = d.analysis_type_id")
			->where("d.id = :direction_id", [
				":direction_id" => $id
			]);
		if (!empty($except)) {
			$query->andWhere("ap.id not in (". implode(",", $except) .")");
		}
		$rows = $query->queryAll();
		foreach ($rows as &$row) {
			$row["checked"] = 0;
			$checked[] = $row;
		}
		return $checked;
	}

	public function getDates($status = null) {
		$query = $this->getDbConnection()->createCommand()
			->select("cast(sending_date as date) as date")
			->from("lis.direction");
		if ($status != null) {
			if (is_array($status)) {
				$query->where("status in (".implode(",", (array) $status).")");
			} else {
				$query->where("status = :status", [
					":status" => $status
				]);
			}
		}
		$rows = $query->group("date")
			->queryAll();
		$dates = [];
		foreach ($rows as $row) {
			$dates[] = $row["date"];
		}
		return $dates;
	}

	/**
	 * Override that method to return data for grid view
	 * @return CDbCommand - Command with query
	 * @throws CDbException
	 */
    public function getGridViewQuery() {
        return $this->getDbConnection()->createCommand()
            ->select("d.*, at.name as analysis_type_id, m.card_number")
            ->from("lis.direction as d")
            ->leftJoin("lis.analysis_type as at", "at.id = d.analysis_type_id")
            ->leftJoin("lis.medcard as m", "m.id = d.medcard_id");
    }

    /**
     * Get count of repeated directions
     * @return int - Count of repeats
     * @throws CDbException
     */
    public function getCountOfRepeats() {
        $row = $this->getDbConnection()->createCommand()
            ->select("count(1) as count")
            ->from("lis.direction")
            ->where("status = 4")
            ->queryRow();
        if ($row) {
            return $row["count"];
        } else {
            return 0;
        }
    }

	/**
	 * Get table provider table widget
	 * @return TableProvider - Table provider instance
	 * @throws CDbException
	 */
	public function getJustCreatedTableProvider() {
		return new TableProvider($this, $this->getDbConnection()->createCommand()
			->select("d.*,
				m.card_number as card_number,
				concat(p.surname, ' ', substring(p.name from 1 for 1), '.', substring(p.patronymic from 1 for 1)) as fio
			")->from("lis.direction as d")
			->join("lis.medcard as m", "d.medcard_id = m.id")
			->join("lis.patient as p", "m.patient_id = p.id")
			->where("d.status = ".LDirection::STATUS_TREATMENT_ROOM)
		);
	}

	public function getSampleRepeatTableProvider() {
		return new TableProvider($this, $this->getDbConnection()->createCommand()
			->select("d.*,
				m.card_number as card_number,
				concat(p.surname, ' ', substring(p.name from 1 for 1), '.', substring(p.patronymic from 1 for 1)) as fio
			")->from("lis.direction as d")
			->join("lis.medcard as m", "d.medcard_id = m.id")
			->join("lis.patient as p", "m.patient_id = p.id")
			->where("d.status = :".LDirection::STATUS_TREATMENT_REPEAT)
		);
	}

	public function getLaboratoryTableProvider() {
		return new TableProvider($this, $this->getDbConnection()->createCommand()
			->select("d.*,
				m.card_number as card_number,
				concat(p.surname, ' ', substring(p.name from 1 for 1), '.', substring(p.patronymic from 1 for 1)) as fio
			")->from("lis.direction as d")
			->join("lis.medcard as m", "d.medcard_id = m.id")
			->join("lis.patient as p", "m.patient_id = p.id")
			->where("d.status = :".LDirection::STATUS_LABORATORY)
		);
	}

    /**
     * Get array with keys for CGridView to display or order
     * @return array - Array with model data
     */
    public function getKeys() {
        return [
            "id" => "№",
            "medcard_id" => "Номер карты",
            "status" => "Статус",
            "department_id" => "Направитель",
            "analysis_type_id" => "Тип анализа"
        ];
    }

    /**
	 * Returns the name of the associated database table.
	 * By default this method returns the class name as the table name.
	 * You may override this method if the table is not named after this convention.
	 * @return string the table name
	 */
    public function tableName() {
        return "lis.direction";
    }

	public $id;
	public $barcode;
	public $status;
	public $comment;
	public $analysis_type_id;
	public $medcard_id;
	public $sender_id;
	public $sending_date;
	public $treatment_room_employee_id;
	public $laboratory_employee_id;
	public $history;
	public $ward_id;
	public $enterprise_id;
	public $mis_medcard;
}