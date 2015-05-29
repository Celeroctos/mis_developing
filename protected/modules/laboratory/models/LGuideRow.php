<?php

class LGuideRow extends ActiveRecord {

	public function findValues($rowId) {
		$query = $this->getDbConnection()->createCommand()
			->select("c.position, v.*")
			->from("lis.guide_row as r")
			->join("lis.guide_value as v", "v.guide_row_id = r.id")
			->join("lis.guide_column as c", "c.id = v.guide_column_id")
			->where("r.id = :id")
			->order("c.position");
		return $query->queryAll(true, [
			":id" => $rowId
		]);
	}

	public function findValueWithDisplay($rowId, $displayId) {
		$query = $this->getDbConnection()->createCommand()
			->select("v.id as id, v.value as value, c.name as name, c.type as type, c.position as position")
			->from("lis.guide_row as r")
			->join("lis.guide_value as v", "v.guide_row_id = r.id")
			->join("lis.guide_column as c", "v.guide_column_id = c.id")
			->where("r.id = :row_id and c.id = :display_id");
		return $query->queryRow(true, [
			":row_id" => $rowId,
			":display_id" => $displayId
		]);
	}

	public function findValueWithDisplayByName($rowId, $display) {
		$query = $this->getDbConnection()->createCommand()
			->select("v.id as id, v.value as value, c.name as name, c.type as type, c.position as position")
			->from("lis.guide_row as r")
			->join("lis.guide_value as v", "v.guide_row_id = r.id")
			->join("lis.guide_column as c", "v.guide_column_id = c.id")
			->where("r.id = :row_id and regexp_replace(lower(c.name), '\\s', '') = regexp_replace(lower(:display), '\\s', '')");
		return $query->queryRow(true, [
			":row_id" => $rowId,
			":display" => $display
		]);
	}

	public function tableName() {
		return "lis.guide_row";
	}
}