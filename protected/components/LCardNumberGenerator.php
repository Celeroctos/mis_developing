<?php

class LCardNumberGenerator {

	const PREFIX = "";
	const DELIMITER = "/";
	const POSTFIX = "-Л";

	public static function getGenerator() {
		if (self::$_generator == null) {
			return self::$_generator = new LCardNumberGenerator();
		} else {
			return self::$_generator;
		}
	}

	public function generate() {
		$row = Yii::app()->getDb()->createCommand()
			->select("count(id) + 1 as index, substring(extract(year from now())::text from 3 for 4) as year")
			->from("lis.medcard as m")
			->group("m.year")
			->where("m.year = year")
			->queryRow();
		if (!$row) {
			$row = [ "index" => 1, "year" => substr(date("Y"), 2, 2) ];
		}
		return static::PREFIX.$row["index"].static::DELIMITER.$row["year"].static::POSTFIX;
	}

	private static $_generator = null;
}