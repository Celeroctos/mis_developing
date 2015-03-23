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
	 * Find all analysis sample types by it's id
	 * @param int $id - Analysis type id
	 * @return array - Array with sample types
	 * @throws CDbException
	 */
	public function findSampleTypes($id) {
		$query = $this->getDbConnection()->createCommand()
			->select("s.*")
			->from("lis.analysis_type_to_sample_type as a_s")
			->join("lis.sample_type as s", "s.id = a_s.sample_type_id")
			->where("a_s.analysis_type_id = :id", [
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
	public function saveAnalysisParameters($id, array $ids) {
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
	 * Add analysis sample types
	 * @param int $id - Analysis type id
	 * @param array $ids - Array with sample types ids
	 * @return int - Count of saved sample types
	 */
	public function saveSampleTypes($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->insert("lis.analysis_type_to_sample_type", [
					"sample_type_id" => $i,
					"analysis_type_id" => $id
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
				->delete("lis.analysis_type_to_analysis_type_parameter", "analysis_type_parameter = :analysis_type_parameter and analysis_type = :analysis_type", [
					":analysis_type_parameter" => $i,
					":analysis_type" => $id
				]);
		}
		return $rows;
	}

	/**
	 * Drop analysis sample types
	 * @param int $id - Analysis type id
	 * @param array $ids - Array with sample types ids
	 * @return int - Count of removed sample types
	 * @throws CDbException
	 */
	public function dropSampleTypes($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->delete("lis.analysis_type_to_sample_type", "sample_type_id = :sample_type_id and analysis_type_id = :analysis_type_id", [
					":sample_type_id" => $i,
					":analysis_type_id" => $id
				]);
		}
		return $rows;
	}
}