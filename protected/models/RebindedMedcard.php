<?php
class RebindedMedcard extends MisActiveRecord  {
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'mis.rebinded_medcards';
    }

    public function primaryKey()
    {
        return 'id';
        // ��� ���������� ���������� ����� ������� ������������ ������:
        // return array('pk1', 'pk2');
    }
}