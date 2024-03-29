<?php
class QueueGrid extends MisActiveRecord {
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
    public $id;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'hospital.queue_grid';
    }

    public function primaryKey() {
        return 'id';
    }

    public function rules() {
        return array(
            array(
                'id, birthday, pregnant_term, ward_name, ward_id, fio, direction_id, card_number, write_type', 'safe', 'on' => 'grid.view'
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
            'comission_type_desc' => 'Тип записи',
            'hospitalization_date' => 'Дата госпитализации',
            'age' => 'Возраст',
            'card_number' => 'Карта',
            'write_type' => 'Тип записи'
        );
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
                )),
            ),
            array(
                'type' => 'raw',
                'value' => '{{%ward_name%|trim}}',
                'name' => 'ward_name',
                'filter' => Ward::model()->getAllForListview()
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
                )),
            ),
            array(
                'type' => 'raw',
                'value' => '%hospitalization_date%',
                'name' => 'hospitalization_date',
                'filter' => CHtml::activeTextField($this, 'hospitalization_date', array(
                    'style' => 'max-width: 100px;'
                )),
            ),
           /* array(
                'class'=>'CButtonColumn',
                'template'=>'{change_comission_date}',
                'buttons'=>array(
                    'change_comission_date' => array(
                        'label' => 'Определить дату комиссии',
                        'imageUrl' => Yii::app()->request->baseUrl.'/images/icons/evolution-calendar.png',
                        'url' => 'array("main/fieldsUpdate", "id" => $data->id)',
                        'options' => array(
                            'id' => 'hd'.$this->direction_id,
                            'class' => 'changeHospitalizationDate',
                            'ajax' => array(
                                'type' =>'GET',
                                'url' => "js:$(this).attr('href')",
                                'update' => '#fieldsUpdate',
                            ),
                        ),
                    )
                ),
            ), */
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('direction_id', $this->direction_id);
        $criteria->compare('fio', $this->fio);
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