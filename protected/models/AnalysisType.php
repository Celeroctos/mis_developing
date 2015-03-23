<?php

/**
 * AR-модель для работы с analysistype_params
 */
class AnalysisType extends MisActiveRecord {

//    public $id;
//    public $name;
//    public $short_name;
//    public $automatic;
//    public $manual;
    public $metodics_n;

    const UNDEF_ID = 0;
    const AUTOMATIC_ID = 1;
    const MANUAL_ID = 2;
    const UNDEF_NAME = 'Не определена';
    const AUTOMATIC_NAME = 'Автоматическая';
    const MANUAL_NAME = 'Ручная';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('metodics', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 200),
            array('short_name', 'length', 'max' => 20),
            array('sample_types', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, short_name, metodics, sample_types', 'safe', 'on' => 'search'),
        );
    }

    public function tableName() {
        return 'lis.analysis_types';
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'analysisTypeSamples' => array(self::HAS_MANY, 'AnalysisTypeSample', 'analysis_type_id'),
            'analysisTypeParams' => array(self::HAS_MANY, 'AnalysisTypeParam', 'analysis_type_id'),
            'analyzerTypeAnalysises' => array(self::HAS_MANY, 'AnalyzerTypeAnalysis', 'analysis_type_id'),
            'directions' => array(self::HAS_MANY, 'Direction', 'analysis_type_id'),
            'analysisTypeParams' => array(self::MANY_MANY, 'AnalysisParam',
                'lis.analysis_type_params(analysis_type_id, analysis_param_id)'),
            'analysisTypeSamplesTypes' => array(self::MANY_MANY, 'AnalysisSampleType',
                'lis.analysis_types_samples(analysis_type_id, analysis_sample_id)'),
            'analyserTypeAnalysis' => array(self::MANY_MANY, 'AnalyserType',
                'lis.analyzer_type_analysis(analysis_type_id, analyser_type_id)'),
        );
    }

    /**
     * Список для выпадающего списка (см. dropDownList Yii)
     * @param string $typeQuery Добавить/обновить
     * @return array
     */
    public static function getAnalysisTypeListData($typeQuery) {
        $model = new AnalysisType;
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name';
        $criteria->order = 'name';
        $analysistypeList = $model->findAll($criteria);
        $ret = CHtml::listData($typeQuery == 'insert' ?
                                CMap::mergeArray([
                                    [
                                        'id' => null,
                                        'name' => '',
                                    ]
                                        ], $analysistypeList) : $analysistypeList, 'id', 'name'
        );
        return $ret;
    }

    /*
      public function getRows($filters, $sidx = false, $sord = false, $start = false, $limit = false) {
      $connection = Yii::app()->db;
      $analysistypes = $connection->createCommand()
      ->select('at.*')
      ->from('lis.analysis_types at');

      if($filters !== false) {
      $this->getSearchConditions($analysistypes, $filters, array(
      ), array(
      'at' => array('analysis_type', 'name')
      ), array(
      'analysis_type' => 'name'
      ));
      }

      if($sidx !== false && $sord !== false) {
      $analysistypes->order($sidx.' '.$sord);
      }
      if($start !== false && $limit !== false) {
      $analysistypes->limit($limit, $start);
      }

      return $analysistypes->queryAll();
      }
     */
    /*
      public function getOne($id) {
      try {
      $connection = Yii::app()->db;
      $analysistype = $connection->createCommand()
      ->select('at.*')
      ->from('lis.analysis_types at')
      ->where('at.id = :id', array(':id' => $id))
      ->queryRow();

      return $analysistype;

      } catch(Exception $e) {
      echo $e->getMessage();
      }
      }
     */

    public function attributeLabels() {
        return [
            'id' => '#ID',
            'name' => 'Наименование типа анализа',
            'short_name' => 'Краткое наименование типа анализа',
            'metodics' => 'Методика:',
#            'param_count'=>'Количество параметров',
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
            $criteria->compare('name', $this->name, true);
            $criteria->compare('short_name', $this->short_name, true);
            $criteria->compare('metodics', $this->metodics, true);
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
                    'short_name',
                    'metodics'
                ],
                'defaultOrder' => [
                    'name' => CSort::SORT_ASC,
                ],
            ],
        ]);
    }

    /**
     * Используется в CGridView
     * @return array
     */
    public function getMetodics($id) {
        switch ($id) {
            case self::UNDEF_ID:
                return self::UNDEF_NAME;
                break;
            case self::AUTOMATIC_ID:
                return self::AUTOMATIC_NAME;
                break;
            case self::MANUAL_ID:
                return self::MANUAL_NAME;
                break;
            default:
                return 'Не указано';
                break;
        }
    }

}
