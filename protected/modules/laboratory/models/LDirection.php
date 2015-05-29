<?php

class LDirection extends ActiveRecord {

	const STATUS_TREATMENT_ROOM = 1;
	const STATUS_LABORATORY = 2;
	const STATUS_READY = 3;
	const STATUS_TREATMENT_REPEAT = 4;
	const STATUS_CLOSED = 5;

	public function relations() {
		return [
			"medcard" => [ self::BELONGS_TO, "LMedcard", "medcard_id", "with" => "patient" ],
			"analysis_type" => [ self::BELONGS_TO, "LAnalysisType", "analysis_type_id" ],
			"treatment_room_employee" => [ self::BELONGS_TO, "Doctors", "treatment_room_employee_id" ],
			"laboratory_employee" => [ self::BELONGS_TO, "Doctors", "laboratory_employee_id" ],
		];
	}

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

	public function getCountOf($status) {
		$row = $this->getDbConnection()->createCommand()
			->select("count(1) as count")
			->from("lis.direction")
			->where("status = :status", [
				":status" => $status
			])
			->queryRow();
		if ($row) {
			return $row["count"];
		} else {
			return 0;
		}
	}

	public function getAnalyzer($id) {
		return $this->getDbConnection()->createCommand()
			->select("a.*")
			->from("lis.direction as d")
			->join("lis.analyzer_type_to_analysis_type as at_at", "at_at.analysis_type_id = d.analysis_type_id")
			->join("lis.analyzer as a", "at_at.analyzer_type_id = a.analyzer_type_id")
			->where("d.id = :id", [
				":id" => $id
			])->queryRow();
	}

	public static function listStatuses() {
		return [
			static::STATUS_TREATMENT_ROOM => "Находится в процедурном кабинете",
			static::STATUS_LABORATORY => "Находится в лаборатории",
			static::STATUS_READY => "Анализ произведен",
			static::STATUS_TREATMENT_REPEAT => "Отправлено на повторный забор образца",
			static::STATUS_CLOSED => "Выполнено",
		];
	}

    public function tableName() {
        return "lis.direction";
    }
}