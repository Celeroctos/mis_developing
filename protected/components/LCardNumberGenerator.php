<?php

class LCardNumberGenerator {

	public static function getGenerator() {
		if (self::$_generator == null) {
			return self::$_generator = new LCardNumberGenerator();
		} else {
			return self::$_generator;
		}
	}

	public function generate() {
		$row = Yii::app()->getDb()->createCommand()
			->select("card_number")
			->from("lis.medcard")
			->offset("card_number")
			->limit(1)
			->queryRow();
		$generator = new CardnumberGenerator();
		$rule = MedcardRule::model()->find("name = :name", [
			":name" => "ЛКП"
		]);
		if ($row !== null) {
			$generator->setPrevNumber($row["card_number"]);
		} else {
			$generator->setPrevNumber("");
		}
		if ($rule != null) {
			$number = $generator->generateNumber($rule["id"]);
		} else {
			throw new CException("Can't resolve medcard rule for laboratory, required name is \"ЛКП\"");
		}
		return $number;
	}

	private static $_generator = null;
}