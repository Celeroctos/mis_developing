<?php

class LCardNumberGenerator {

	const PREFIX = "";
	const DELIMITER = "/";
	const POSTFIX = "#Л";

	public static function getGenerator() {
		if (self::$_generator == null) {
			return self::$_generator = new LCardNumberGenerator();
		} else {
			return self::$_generator;
		}
	}

	public function generate() {
		$row = Yii::app()->getDb()->createCommand()
			->select("max(id) + 1 as index, extract(year from now()) as year")
			->from("lis.medcard as m")
			->group("m.year")
			->where("m.year = year")
			->queryRow();
		return static::PREFIX.$row["index"].static::DELIMITER.$row["year"].static::POSTFIX;
	}

	private static $_generator = null;
}