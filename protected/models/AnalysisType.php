<?php

class AnalysisType extends GActiveRecord {

	/**
	 * Get analysis type model
	 * @param null $className - Name of class or null
	 * @return AnalysisType - Cached model
	 */
	public static function model($className = null) {
		return parent::model($className);
	}

	public function getForm() {
		return new AnalysisTypeForm();
	}

	public function rules() {
		return [
			[ "short_name", "length", "max" => 20 ],
			[ "name", "length", "max" => 255 ]
		];
	}

	public function tableName() {
		return "lis.analysis_type";
	}

	/**
	 * Find all analysis parameters by it's id
	 * @param int $id - Analysis type id
	 * @return array - Array with parameters
	 * @throws CDbException
	 */
	public function findAnalysisParameters($id) {
		$query = $this->getDbConnection()->createCommand()
			->select("p.*")
			->from("lis.analysis_type_to_analysis_type_parameter as a_p")
			->join("lis.analysis_type_parameter as p", "p.id = a_p.analysis_type_parameter")
			->where("a_p.analysis_type = :id", [
				":id" => $id
			]);
		return $query->queryAll();
	}

	/**
	 * Add analysis parameters
	 * @param int $id - Analysis type id
	 * @param array $ids - Array with parameters ids
	 * @return int - Count of saved parameters
	 */
	public function addAnalysisParameters($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->insert("lis.analysis_type_to_analysis_type_parameter", [
					"analysis_type_parameter" => $i,
					"analysis_type" => $id
				]);
		}
		return $rows;
	}

	/**
	 * Drop analysis parameters
	 * @param int $id - Analysis type id
	 * @param array $ids - Array with parameters ids
	 * @return int - Count of removed rows
	 * @throws CDbException
	 */
	public function dropAnalysisParameters($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->delete("lis.analysis_type_to_analysis_type_parameter", [
					"analysis_type_parameter" => $i,
					"analysis_type" => $id
				]);
		}
		return $rows;
	}
}