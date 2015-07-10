<?php

class Laboratory_CardNumberGenerator {

	const PREFIX = '';
	const DELIMITER = '/';
	const POSTFIX = '-Л';

	public static function getGenerator() {
		if (self::$_generator == null) {
			return self::$_generator = new Laboratory_CardNumberGenerator();
		} else {
			return self::$_generator;
		}
	}

	public function generate() {
		$row = Yii::app()->getDb()->createCommand()
			->select('count(id) + 1 as index, extract(year from now())::text as year')
			->from('lis.medcard as m')
			->group('m.year')
			->where('m.year = year')
			->queryRow();
		if (!$row) {
			$row = [
				'year' => date('Y'),
				'index' => 1,
			];
		}
		return static::PREFIX.$row['index'].static::DELIMITER.substr($row['year'], 2, 2).static::POSTFIX;
	}

	private static $_generator = null;
}