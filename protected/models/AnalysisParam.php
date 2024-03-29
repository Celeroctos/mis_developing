<?php

/**
 * AR-модель для работы с analysis_params
 */
class AnalysisParam extends MisActiveRecord {
    /*    public $id;
      public $name;
      public $long_name;
      public $comment;
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'lis.analysis_params';
    }

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'length', 'max' => 30],
            ['long_name', 'length', 'max' => 200],
            ['comment', 'safe'],
#            ['name, long_name, comment', 'type', 'type'=>'string', 'on'=>'analysisparams.update'],
            ['id', 'type', 'type' => 'integer'], //[controller].[action]
#            ['name', 'required', 'on'=>'analysisparams.create'],
#            ['name, long_name, comment', 'type', 'type'=>'string', 'on'=>'analysisparams.create'],
#            ['id', 'type', 'type'=>'integer', 'on'=>'analysisparams.create'], //[controller].[action]
            ['id, name, long_name, comment', 'safe', 'on' => 'analysisparams.search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'analysisTypeTemplates' => array(self::HAS_MANY, 'AnalysisTypeParams', 'analysis_param_id'),
            'analysisTypeParams' => array(self::MANY_MANY, 'AnalysisType',
                'lis.analysis_type_params(analysis_type_id, analysis_param_id)'),
        );
    }

    /**
     * Список для выпадающего списка (см. dropDownList Yii)
     * @param string $typeQuery Добавить/обновить
     * @return array
     */
    public static function getAnalysisParamListData($typeQuery) {
        $model = new AnalysisParam;
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name';
        $criteria->order = 'name';
        $analysisparamList = $model->findAll($criteria);

        return CHtml::listData(
                        ($typeQuery == 'insert') ? CMap::mergeArray([
                                    [
                                        'id' => null,
                                        'name' => '',
                                    ]
                                        ], $analysisparamList) :
                                $analysisparamList, 'id', 'name'
        );
    }

    /*
      public function getRows($filters, $sidx = false, $sord = false, $start = false, $limit = false) {
      $connection = Yii::app()->db;
      $analysisparams = $connection->createCommand()
      ->select('at.*')
      ->from('lis.analysis_params ap');

      if($filters !== false) {
      $this->getSearchConditions($analysisparams, $filters, array(
      ), array(
      'ap' => array('analysis_param', 'name')
      ), array(
      'analysis_param' => 'name'
      ));
      }

      if($sidx !== false && $sord !== false) {
      $analysisparams->order($sidx.' '.$sord);
      }
      if($start !== false && $limit !== false) {
      $analysisparams->limit($limit, $start);
      }

      return $analysisparams->queryAll();
      }
     */
    /*
      public function getOne($id) {
      try {
      $connection = Yii::app()->db;
      $analysisparam = $connection->createCommand()
      ->select('ap.*')
      ->from('lis.analysis_params ap')
      ->where('ap.id = :id', array(':id' => $id))
      ->queryRow();

      return $analysisparam;

      } catch(Exception $e) {
      echo $e->getMessage();
      }
      }
     */

    public function attributeLabels() {
        return [
            'id' => '#ID',
            'name' => 'Краткое наименование параметра анализа',
            'long_name' => 'Полное наименование параметра анализа',
            'comment' => 'Примечания'
        ];
    }

    /**
     * Метод для поиска в CGridView
     */
    public function search() {
        $criteria = new CDbCriteria;

        //        if($this->validate())
        {
            $criteria->compare('id', $this->id);
            $criteria->compare('name', $this->name, true);
            $criteria->compare('long_name', $this->long_name, true);
            $criteria->compare('comment', $this->comment, true);
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
                    'name',
                    'long_name',
                    'comment'
                ],
                'defaultOrder' => [
                    'name' => CSort::SORT_ASC,
                ],
            ],
        ]);
    }

}
