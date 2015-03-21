<?php
class HospitalizationGrid extends MisActiveRecord {
	public $defaultPageSize = 10;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'hospital.hospitalization_grid';
    }
    public function primaryKey() {
        return 'id';
    }
    public function attributeLabels() {
        return array(
            'id' => 'ID'
        );
    }
}
?>