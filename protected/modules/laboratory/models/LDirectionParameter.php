<?php

class LDirectionParameter extends ActiveRecord {

	public function findByDirection($id) {
		$rows = $this->getDbConnection()->createCommand()
			->select('p.*')
			->from('lis.analysis_type_parameter as p')
			->join('lis.direction_parameter as dp', 'p.id = dp.analysis_type_parameter_id')
			->where('dp.direction_id = :direction_id', [
				':direction_id' => $id
			])->queryAll();
		return $rows;
	}

	public function tableName() {
		return "lis.direction_parameter";
	}
}