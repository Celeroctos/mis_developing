<?php
class PatientsGrid extends MisActiveRecord {
	public $pageSize = 10;
    public $parentController = null;

    public $is_refused;
    public $card_number;
    public $fio;
    public $patient_id;
    public $birthday;
    public $ward_name;
    public $ward_id;
    public $doctor_id;
    public $is_pregnant;
    public $pregnant_term;
    public $type;
    public $create_date;
    public $direction_id;
    public $comission_type_desc;
    public $age;
    public $hospitalization_date;
    public $write_type;
    public $write_type_desc;
    public $id;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'hospital.comission_grid';
    }

    public function primaryKey() {
        return 'id';
    }

    public function rules() {
        return array(
            array(
                'id, birthday, pregnant_term, ward_name, ward_id, fio, card_number, direction_id, hospitalization_date, write_type, write_type_desc', 'safe', 'on' => 'grid.view'
            )
        );
    }

    public function attributeLabels() {
        return array(
            'direction_id' => 'ID',
            'fio' => 'ФИО',
            'birthday' => 'День рождения',
            'ward_name' => 'Отделение',
            'pregnant_term' => 'Срок',
            'write_type_desc' => 'Тип записи',
            'hospitalization_date' => 'Дата госпитализации',
            'age' => 'Возраст',
            'card_number' => 'Карта'
        );
    }

    public function beforeFind() {
        $this->hospitalization_date = implode('-', array_reverse(explode('.', $this->hospitalization_date)));
    }

    // This changes model after finding
    public function afterFind() {
        // Age
        $datetime = new DateTime($this->birthday);
        $interval = $datetime->diff(new DateTime(date("Y-m-d")));
        $this->age = $interval->format("%Y").' лет';

        // Icon, if hospitalization date is not accepted
        if(!$this->hospitalization_date) {
            if(!$this->is_refused) {
                $this->hospitalization_date = '<a href="#" id="qd' . $this->direction_id . '" class="changeHospitalizationDate"><img src="' . Yii::app()->request->baseUrl . '/images/icons/evolution-calendar.png" width="24" height="24" alt="Определить дату" title="Определить дату" ></a>';
            } else {
                $this->hospitalization_date = 'Отказалась';
            }
        } else {
            $this->hospitalization_date = implode('.', array_reverse(explode('-', $this->hospitalization_date)));
        }

        if($this->write_type !== null) {
            $this->write_type_desc = ($this->write_type == 1) ? 'Живая очередь' : 'По записи';
        }
    }

    public function getColumnsModel() {
        return array(
           array(
               'type' => 'raw',
               'value' => '%direction_id%',
               'name' => 'direction_id',
               'filter' => CHtml::activeTextField($this, 'direction_id', array(
                   'style' => 'max-width: 100px;'
               )),
           ),
           array(
               'type' => 'raw',
               'value' => '%fio%',
               'name' => 'fio'
            ),
            array(
                'type' => 'raw',
                'value' => '%card_number%',
                'name' => 'card_number',
                'filter' => CHtml::activeTextField($this, 'card_number', array(
                    'style' => 'max-width: 100px;'
                ))
            ),
           array(
               'type' => 'raw',
               'value' => '%write_type_desc%',
               'name' => 'write_type_desc',
               'filter' => array('Обычная', 'По записи')
           ),
           array(
               'type' => 'raw',
               'value' => '{{%ward_name%|trim}}',
               'name' => 'ward_name',
               'filter' => Ward::model()->getAllForListview(),
               'filter' => CHtml::activeTextField($this, 'ward_name', array(
                   'style' => 'max-width: 150px;'
               ))
           ),
           array(
               'type' => 'raw',
               'value' => '%age%',
               'name' => 'age',
               'filter' => CHtml::activeTextField($this, 'age', array(
                   'style' => 'max-width: 100px;'
               ))
           ),
           array(
               'type' => 'raw',
               'value' => '{{%pregnant_term%|int}}." недель"',
               'name' => 'pregnant_term',
               'filter' => CHtml::activeTextField($this, 'pregnant_term', array(
                   'style' => 'max-width: 100px;'
               ))
           ),
           array(
               'type' => 'raw',
               'value' => '%hospitalization_date%',
               'name' => 'hospitalization_date',
               'filter' => CHtml::activeTextField($this, 'hospitalization_date', array(
                    'style' => 'max-width: 100px;'
               )),
           )
       );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $filter = Yii::app()->request->getParam('filter');
        if($filter != null) {
            foreach($filter as $field => $value) {
                $this->$field = $value;
            }
            /* if($this->hospitalization_date == date('Y-n-j')) {
                 $criteria->compare('is_refused', 1, false, 'OR');
            } */
        }

        $criteria->compare('direction_id', $this->direction_id);
        $criteria->compare('fio', $this->fio, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('ward_id', $this->ward_id);
        $criteria->compare('pregnant_term', $this->pregnant_term);
        $criteria->compare('hospitalization_date', $this->hospitalization_date);
        $criteria->compare('card_number', $this->card_number);

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