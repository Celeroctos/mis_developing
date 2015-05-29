<?php

class LGuideColumn extends ActiveRecord {

	public function findDisplayableAndOrdered($conditions = '', $params = []) {
		$query = $this->getDbConnection()->createCommand()
			->select("*")
			->from($this->tableName())
			->where("type <> 'dropdown' and type <> 'multiple'")
			->andWhere($conditions, $params)
			->order("position");
		return $query->queryAll();
	}

	public function findOrdered($conditions = '', $params = []) {
		$query = $this->getDbConnection()->createCommand()
			->select("*")
			->from($this->tableName())
			->where($conditions, $params)
			->order("position");
		return $query->queryAll();
	}

	public function tableName() {
		return "lis.guide_column";
	}
}