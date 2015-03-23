<?php

/**
 * AR-модель для работы с analysis_params
 */
class AnalysisSampleType extends MisActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('type', 'required'),
            array('type, subtype', 'length', 'max' => 100),
            // The following rule isx used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, subtype', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'analysisTypeSample' => array(self::HAS_MANY, 'AnalysisTypeSample', 'analysis_sample_id'),
            'analysisType_Samples' => array(self::MANY_MANY, 'AnalysisType',
                'lis.analysis_types_samples(analysis_type_id, analysis_sample_id)'),
        );
    }

    public function tableName() {
        return 'lis.analysis_sample_types';
    }

    /**
     * Список для выпадающего списка (см. dropDownList Yii)
     * @param string $typeQuery Добавить/обновить
     * @return array
     */
    public static function getAnalysisSampleTypeListData($typeQuery) {
        $model = new AnalysisSampleType;
        $criteria = new CDbCriteria;
        $criteria->select = "id, concat(concat(concat(type, '('), subtype), ')') as type";
        $criteria->order = "type"; #"concat(concat(concat(type, '('), subtype), ')')";
        $analysissampleList = $model->findAll($criteria);
        $res = CHtml::listData(
                        $typeQuery == 'insert' ? CMap::mergeArray([
                                    [
                                        'id' => null,
                                        'type' => '',
                                    ]
                                        ], $analysissampleList) :
                                $analysissampleList, 'id', 'type'
        );
        ;
        return $res;
    }

    /*
      public function getRows($filters, $sidx = false, $sord = false, $start = false, $limit = false) {
      $connection = Yii::app()->db;
      $analysissamples = $connection->createCommand()
      ->select('at.*')
      ->from('lis.analysis_params ap');

      if($filters !== false) {
      $this->getSearchConditions($analysissamples, $filters, array(
      ), array(
      'ap' => array('analysis_param', 'name')
      ), array(
      'analysis_param' => 'name'
      ));
      }

      if($sidx !== false && $sord !== false) {
      $analysissamples->order($sidx.' '.$sord);
      }
      if($start !== false && $limit !== false) {
      $analysissamples->limit($limit, $start);
      }

      return $analysissamples->queryAll();
      }
     */
    /*
      public function getOne($id) {
      try {
      $connection = Yii::app()->db;
      $analysissample = $connection->createCommand()
      ->select('ast.*')
      ->from('lis.analysis_sample_types ast')
      ->where('ast.id = :id', array(':id' => $id))
      ->queryRow();

      return $analysissample;

      } catch(Exception $e) {
      echo $e->getMessage();
      }
      }
     */

    public function attributeLabels() {
        return [
            'id' => '#ID',
            'type' => 'Тип образца',
            'subtype' => 'Подтип образца',
        ];
    }

    /**
     * Метод для поиска в CGridView
     */
    public function search() {
        $criteria = new CDbCriteria;

        //        if($this->validate())
        {
            $criteria->compare('id', $this->id, false);
            $criteria->compare('type', $this->type, true);
            $criteria->compare('subtype', $this->subtype, true);
        }
        /*        else
          {
          $criteria->addCondition('id=-1');
          }
         */
        return new CActiveDataProvider($this, [
            'pagination' => ['pageSize' => 10],
            'criteria' => $criteria,
            'sort' => [
                'attributes' => [
                    'id',
                    'type',
                    'subtype'
                ],
                'defaultOrder' => [
                    'type' => CSort::SORT_ASC,
                ],
            ],
        ]);
    }

}
