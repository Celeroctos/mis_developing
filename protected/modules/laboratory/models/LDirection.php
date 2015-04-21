<?php

class LDirection extends ActiveRecord {

	const STATUS_JUST_CREATED = 1;
	const STATUS_SAMPLE_DONE = 2;
	const STATUS_ANALYSIS_DONE = 3;
	const STATUS_SAMPLE_REPEAT = 4;

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
	public function getTreatmentTableProvider() {
		return new TableProvider($this, $this->getDbConnection()->createCommand()
			->select("d.*, m.card_number as card_number, d.status as status")
			->from("lis.direction as d")
			->join("lis.medcard as m", "d.medcard_id = m.id")
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
}