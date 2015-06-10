<?php

class MedcardElementEx extends MedcardElement {

	const TYPE_TEXT = 0;
	const TYPE_TEXTAREA = 1;
	const TYPE_DROPDOWN = 2;
	const TYPE_MULTIPLE = 3;
	const TYPE_TABLE = 4;
	const TYPE_NUMBER = 5;
	const TYPE_DATE = 6;
	const TYPE_EXCHANGE = 7;

	public static $render = [
		self::TYPE_TEXT => 'textInput',
		self::TYPE_TEXTAREA => 'textAreaInput',
		self::TYPE_DROPDOWN => 'dropDownInput',
		self::TYPE_MULTIPLE => 'multipleInput',
		self::TYPE_TABLE => 'tableInput',
		self::TYPE_NUMBER => 'numberInput',
		self::TYPE_DATE => 'dateInput',
		self::TYPE_EXCHANGE => 'exchangeInput',
	];

	public static $listTypes = [
		MedcardElementEx::TYPE_DROPDOWN,
		MedcardElementEx::TYPE_MULTIPLE,
		MedcardElementEx::TYPE_EXCHANGE,
	];

	public static $tableTypes = [
		MedcardElementEx::TYPE_TABLE,
	];

	public static $growableTypes = [
		MedcardElementEx::TYPE_DROPDOWN,
	];

	public static function getTypeList() {
		return [
			static::TYPE_TEXT => 'Текстовое поле',
			static::TYPE_TEXTAREA => 'Текстовая область',
			static::TYPE_DROPDOWN => 'Выпадающий список',
			static::TYPE_MULTIPLE => 'Выпадающий список с множественным выбором',
			static::TYPE_TABLE => 'Редактируемая таблица',
			static::TYPE_NUMBER => 'Числовое поле',
			static::TYPE_DATE => 'Дата',
			static::TYPE_EXCHANGE => 'Двухколоночный список'
		];
	}
}