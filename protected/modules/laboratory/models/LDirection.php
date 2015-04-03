<?php

class LDirection extends ActiveRecord {

	public $department_id;

	/**
	 * @return LDirection - Cached model instance
	 */
    public static function model() {
        return parent::model(__CLASS__);
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
            ->select("count(id) as count")
            ->from("lis.direction")
            ->where("status = 3")
            ->queryRow();
        if ($row) {
            return $row["count"];
        } else {
            return 0;
        }
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
}