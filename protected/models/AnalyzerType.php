<?php

/**
 * AR-модель для работы с analysis_params
 */
class AnalyzerType extends MisActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('type', 'required'),
            array('type, name', 'length', 'max' => 100),
            array('notes, analysis_types', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, name, notes, analysis_types', 'safe', 'on' => 'search'),
        );
    }

    public function tableName() {
        return 'lis.analyzer_types';
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'analyzerTypeAnalysises' => array(self::HAS_MANY, 'AnalyzerTypeAnalysis', 'analyser_type_id'),
            'machines' => array(self::HAS_MANY, 'Machine', 'analyzer_type_id'),
            'analyserTypeAnalysis' => array(self::MANY_MANY, 'AnalysisType',
                'lis.analyzer_type_analysis(analysis_type_id, analyser_type_id)'),
        );
    }

    /**
     * Список для выпадающего списка (см. dropDownList Yii)
     * @param string $typeQuery Добавить/обновить
     * @return array
     */
    public static function getAnalyzerTypeListData($typeQuery) {
        $model = new AnalyzerType;
        $criteria = new CDbCriteria;
        $criteria->select = 'id, type';
        $criteria->order = 'type';
        $analyzertypeList = $model->findAll($criteria);

        $qq = CHtml::listData(
                        ($typeQuery == 'insert') ? CMap::mergeArray([
                                    [
                                        'id' => null,
                                        'type' => '',
                                    ]
                                        ], $analyzertypeList) :
                                $analyzertypeList, 'id', 'type'
        );
        return $qq;
    }

    /*
      public function getRows($filters, $sidx = false, $sord = false, $start = false, $limit = false) {
      $connection = Yii::app()->db;
      $analyzertypes = $connection->createCommand()
      ->select('at.*')
      ->from('lis.analysis_params ap');

      if($filters !== false) {
      $this->getSearchConditions($analyzertypes, $filters, array(
      ), array(
      'ap' => array('analysis_param', 'name')
      ), array(
      'analysis_param' => 'name'
      ));
      }

      if($sidx !== false && $sord !== false) {
      $analyzertypes->order($sidx.' '.$sord);
      }
      if($start !== false && $limit !== false) {
      $analyzertypes->limit($limit, $start);
      }

      return $analyzertypes->queryAll();
      }
     */

    public function getOne($id) {
        try {
            $connection = Yii::app()->db;
            $analyzertype = $connection->createCommand()
                    ->select('at.*')
                    ->from('lis.analyzer_types at')
                    ->where('at.id = :id', array(':id' => $id))
                    ->queryRow();

            return $analyzertype;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function attributeLabels() {
        return [
            'id' => '#ID',
            'type' => 'Тип анализатора',
            'name' => 'Название анализатора',
            'notes' => 'Пометки'
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
            $criteria->compare('name', $this->name, true);
            $criteria->compare('notes', $this->notes, true);
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
                    'name',
                    'notes'
                ],
                'defaultOrder' => [
                    'type' => CSort::SORT_ASC,
                ],
            ],
        ]);
    }

}
