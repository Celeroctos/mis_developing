<?php
class SheduleSetted extends MisActiveRecord {
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'mis.doctor_shedule_setted';
    }

    public function getOne($id) {
        try {


        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }


    public function getRows() {

    }

    public function getByEnterprise($id) {
        try {

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>