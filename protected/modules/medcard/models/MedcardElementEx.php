<?php

class MedcardElementEx extends MedcardElement {

    const FLAG_DEFAULT = 0x0000;
    const FLAG_GROWABLE = 0x0001;
    const FLAG_WRAPPED = 0x0002;
    const FLAG_REQUIRED = 0x0004;
    const FLAG_DYNAMIC = 0x0008;
    const FLAG_RECORD = 0x0010;
    const FLAG_CATEGORY = 0x0020;

    const FLAG_NOT_PRINT_VALUES = 0x0040;
    const FLAG_HIDE_LABEL_BEFORE = 0x0080;

	const TYPE_TEXT = 0;
	const TYPE_TEXTAREA = 1;
	const TYPE_DROPDOWN = 2;
	const TYPE_MULTIPLE = 3;
	const TYPE_TABLE = 4;
	const TYPE_NUMBER = 5;
	const TYPE_DATE = 6;
	const TYPE_EXCHANGE = 7;
    const TYPE_CATEGORY = 8;

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

	public static $render = [
		self::TYPE_TEXT => 'textInput',
		self::TYPE_TEXTAREA => 'textAreaInput',
		self::TYPE_DROPDOWN => 'dropDownInput',
		self::TYPE_MULTIPLE => 'selectInput',
		self::TYPE_TABLE => 'tableInput',
		self::TYPE_NUMBER => 'numberInput',
		self::TYPE_DATE => 'dateInput',
		self::TYPE_EXCHANGE => 'exchangeInput',
	];

    public function fetchWithGreeting($category) {
        $rows = $this->getDbConnection()->createCommand()
            ->select('e.*')
            ->from('mis.medcard_elements as e')
            ->where('e.categorie_id = :id', [
                ':id' => $category
            ])->queryAll();
        return $rows;
    }

	public static function getTypeList() {
		return [
			static::TYPE_TEXT => 'Текстовое поле',
			static::TYPE_TEXTAREA => 'Текстовая область',
			static::TYPE_DROPDOWN => 'Выпадающий список',
			static::TYPE_MULTIPLE => 'Выпадающий список с множественным выбором',
			static::TYPE_TABLE => 'Редактируемая таблица',
			static::TYPE_NUMBER => 'Числовое поле',
			static::TYPE_DATE => 'Дата',
			static::TYPE_EXCHANGE => 'Двухколоночный список',
            static::TYPE_CATEGORY => 'Категорий',
		];
	}
}