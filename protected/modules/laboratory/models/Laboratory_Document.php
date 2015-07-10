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
            static::TYPE_UNKNOWN => '����������',
            static::TYPE_PASSPORT => '�������',
            static::TYPE_CERTIFICATE_OF_BIRTH => '������������� � ��������',
            static::TYPE_RESIDENCE => '��� �� ����������',
            static::TYPE_IDENTIFICATION => '������������� ��������',
            static::TYPE_OTHER => '������ ��������',
        ];
    }

	public function tableName() {
		return 'lis.document';
	}
}