<?php

class Laboratory_Document extends ActiveRecord {

	const TYPE_UNKNOWN = 0;
    const TYPE_PASSPORT = 1;
    const TYPE_CERTIFICATE_OF_BIRTH = 2;
    const TYPE_RESIDENCE = 3;
    const TYPE_IDENTIFICATION = 4;
    const TYPE_OTHER = 5;

    public static function listTypes() {
        return [
            static::TYPE_UNKNOWN => 'Неизвестно',
            static::TYPE_PASSPORT => 'Паспорт',
            static::TYPE_CERTIFICATE_OF_BIRTH => 'Свидетельство о рождении',
            static::TYPE_RESIDENCE => 'Вид на жительство',
            static::TYPE_IDENTIFICATION => 'Удостоверение личности',
            static::TYPE_OTHER => 'Другой документ',
        ];
    }

	public function tableName() {
		return 'lis.document';
	}
}