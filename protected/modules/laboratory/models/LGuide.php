<?php

class LGuide extends ActiveRecord {

	public function tableName() {
		return "lis.guide";
	}

	public function findValues($guideId) {
		$query = $this->getDbConnection()->createCommand()
			->select("id")
			->from("lis.guide_row")
			->where("guide_id = :guide_id");
		$rows = $query->queryAll(true, [
			":guide_id" => $guideId
		]);
		$values = [];
		foreach ($rows as $row) {
			$values[] = LGuideRow::model()->findValues(
				$row["id"]
			);
		}
		return $values;
	}

	public function findValuesWithDisplayByName($guideName, $columnName) {
		$guide = $this->find("regexp_replace(lower(name), '\\s', '') = regexp_replace(lower('{$guideName}'), '\\s', '')");
		if ($guide == null) {
			throw new CException("Справочник с таким именем не существует \"{$guideName}\"");
		}
		$query = $this->getDbConnection()->createCommand()
			->select("id")
			->from("lis.guide_row")
			->where("guide_id = :guide_id");
		$rows = $query->queryAll(true, [
			":guide_id" => $guide->id
		]);
		$values = [];
		foreach ($rows as $row) {
			$list = LGuideRow::model()->findValueWithDisplayByName(
				$row["id"], $columnName
			);
			$values[$list["id"]] = $list["value"];
		}
		return $values;
	}

	public function findValuesWithDisplay($guideId, $displayId) {
		$query = $this->getDbConnection()->createCommand()
			->select("*")
			->from("lis.guide_row")
			->where("guide_id = :guide_id");
		$rows = $query->queryAll(true, [
			":guide_id" => $guideId
		]);
		$values = [];
		foreach ($rows as $row) {
			$values[] = LGuideRow::model()->findValueWithDisplay(
				$row["id"], $displayId
			);
		}
		return $values;
	}
}