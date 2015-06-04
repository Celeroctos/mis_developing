<?php
class DesignationsGrid extends MisActiveRecord {
    public $pageSize = 10;
    public $parentController = null;

    public $id;
    public $drug_id;
    public $use_id;
    public $dosage;
    public $per_day;
    public $eat_interval;
    public $eat_type;
    public $date_end;
    public $comment;
    public $date_begin;

    public $cancel;
    public $print;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'hospital.designations';
    }

    public function primaryKey() {
        return 'id';
    }

    public function rules() {
        return array(
            array(
                'id, drug_id, use_id, dosage, per_day, eat_interval, eat_type, date_end, comment, date_begin', 'safe', 'on' => 'grid.view'
            )
        );
    }

    public function attributeLabels() {
        return array(
            'drug_id' => 'Медикамент',
            'id' => 'ID',
            'use_id' => 'Способ применения',
            'dosage' => 'Дозировка',
            'per_day' => 'В день',
            'date_end' => 'Выполнять до',
            'comment' => 'Комментарий',
            'date_begin' => 'Дата назначения',
            'cancel' => '',
            'print' => ''
        );
    }

    public function beforeFind() {

    }

    // This changes model after finding
    public function afterFind() {
        // Icon, if patient didn't showed by doctor
        $this->print = '<a href="#" title="Напечатать назначение"><span class="glyphicon glyphicon-print"></span></a>';
        $this->cancel = '<a href="#" title="Отменить назначение"><span class="glyphicon glyphicon-remove"></span></a>';
    }

    public function getColumnsModel() {
        return array(
            array(
                'type' => 'raw',
                'value' => '%date_begin%',
                'name' => 'date_begin'
            ),
            array(
                'type' => 'raw',
                'value' => '%drug_id%',
                'name' => 'drug_id'
            ),
            array(
                'type' => 'raw',
                'value' => '%cancel%',
                'name' => 'cancel'
            ),
            array(
                'type' => 'raw',
                'value' => '%print%',
                'name' => 'print'
            )
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('drug_id', $this->drug_id, true);

        $dataProvider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $this->pageSize,
                'route' => 'grid/index'
            ),
            'sort' => array(
                'route' => 'grid/index',
                'attributes' => $this->getSortAttributes($this->getColumnsModel())
            )
        ));

        return $dataProvider;
    }


    private function getSortAttributes($gridModel) {
        $attrs = array();
        foreach($gridModel as $element) {
            $attrs[] = $element['name'];
        }

        return $attrs;
    }

    public function getConnection() {
        return Yii::app()->db;
    }
}
?>