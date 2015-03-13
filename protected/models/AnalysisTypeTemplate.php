<?php
/**
 * Класс для работы с отделениями
 */
class AnalysisTypeTemplate extends MisActiveRecord 
{
/*	public $id;
	public $name;
	public $enterprise_id;
	public $rule_id;*/
    const TRUE_ID=1;
    const FALSE_ID=0;
    const TRUE_NAME='Да';
    const FALSE_NAME='Нет';
	
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	
	public function relations()
	{
		return [
			'analysis_types'=>[self::BELONGS_TO, 'AnalysisType', 'analysis_type_id'],
			'analysis_params'=>[self::BELONGS_TO, 'AnalysisParam', 'analysis_param_id'],
		];
	}
	
    public function rules()
    {
        return [
            ['analysis_type_id, analysis_param_id', 'required', 'on'=>'analysistypetemplate.update'],
            ['analysis_type_id, analysis_param_id, is_default, seq_number', 'type', 'type'=>'integer', 'on'=>'analysistypetemplate.update'], //[controller].[action]
            ['id, is_default', 'safe', 'on'=>'analysistypetemplate.update'],

            ['analysis_type_id, analysis_param_id', 'required', 'on'=>'analysistypetemplate.create'],
            ['analysis_type_id, analysis_param_id, is_default, seq_number', 'type', 'type'=>'integer', 'on'=>'analysistypetemplate.create'], //[controller].[action]
            ['id, is_default, analysis_type', 'safe', 'on'=>'analysistypetemplate.create'],

            ['id, analysis_type_id, analysis_param_id, is_default, analysis_type', 'safe', 'on'=>'analysistypetemplate.search'],
        ];
    }

    public function tableName()
    {
        return 'lis.analysis_type_templates';
    }
	
    public function attributeLabels() {
        return [
            'id'=>'#ID',
            'analysis_type'=>'Наименование типа анализа',
            'analysis_param'=>'Наименование параметра анализа',
            'analysis_type_id'=>'Наименование типа анализа',
            'analysis_param_id'=>'Наименование параметра анализа',
            'is_default'=>'Включен по умолчанию?',
            'seq_number'=>'Порядковый номер параметра'
            
        ];
    }

    /**
    * Используется в CGridView
    * @return array
    */
    public function getBool($id)
    {
        switch($id)
        {
            case self::TRUE_ID:
                return self::TRUE_NAME;
                break;
            case self::FALSE_ID:
                return self::FALSE_NAME;
                break;
            default:
                return 'Не указано';
                break;
        }
    }
    
	/**
	 * Метод для поиска в CGridView
	 */
	public function search()
	{
        if (!$this->analysis_type_id)  $this->analysis_type_id = -1;
		$criteria=new CDbCriteria;
		$criteria->with=[['analysis_types'=>['together'=>true, 'joinType'=>'LEFT JOIN']],
        ['analysis_params'=>['together'=>true, 'joinType'=>'LEFT JOIN']]];

        if ($this->analysis_type_id)  
            $criteria->compare('analysis_type_id', $this->analysis_type_id);
        if ($this->analysis_param_id)  
            $criteria->compare('analysis_param_id', $this->analysis_param_id);
		
		
		return new CActiveDataProvider($this, [
			'pagination'=>['pageSize'=>15],
			'criteria'=>$criteria,
            'sort'=>[
                    'attributes'=>[
                        'seq_number',
                    ],
                    'defaultOrder'=>[
                            'seq_number'=>CSort::SORT_ASC,
                        ],
            ]
		]);
	}
	/*
    public function getOne($id) {
        try {
            $connection = Yii::app()->db;
            $analysistypetemplate = $connection->createCommand()
                ->select('att.*, at.name as analysis_type, ap.name as analysis_param')
                ->from('lis.analysis_type_templates att')
				->leftJoin(AnalysisType::model()->tableName().' at', 'at.id = att.analysis_type_id')
                ->leftJoin(AnalysisParam::model()->tableName().' ap', 'ap.id = att.analysis_param_id')
                ->where('att.id = :id', array(':id' => $id))
                ->queryRow();

            return $analysistypetemplate;
                                            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
	*/
    /*
    public function getRows($filters, $sidx = false, $sord = false, $start = false, $limit = false) {
        $connection = Yii::app()->db;
        $analysistypetemplate = $connection->createCommand()
                ->select('att.*, at.name as analysis_type, ap.name as analysis_param')
                ->from('lis.analysis_type_templates att')
                ->leftJoin(AnalysisType::model()->tableName().' at', 'at.id = att.analysis_type_id')
                ->leftJoin(AnalysisParam::model()->tableName().' ap', 'ap.id = att.analysis_param_id');

        if($filters !== false) {
            $this->getSearchConditions($analysistypetemplate, $filters, array(

            ), array(
                'att' => array('id'),
                'at' => array('analysis_type'),
				'ap' => array('analysis_param')
            ), array(
                'analysis_type' => 'name',
				'analysis_param' => 'name',
            ));
        }

        if($sidx !== false && $sord !== false ) {
            $analysistypetemplate->order($sidx.' '.$sord);
        }
        if($start !== false && $limit !== false) {
            $analysistypetemplate->limit($limit, $start);
        }

        return $analysistypetemplate->queryAll();
    }
	*/
	/*
    public function getAll() {
        try {
            $connection = Yii::app()->db;
            $analysistypetemplate = $connection->createCommand()
                ->select('att.*, at.name as analysis_type, ap.name as analysis_param')
                ->from('lis.analysis_type_templates att')
                ->leftJoin(AnalysisType::model()->tableName().' at', 'at.id = att.analysis_type_id')
                ->leftJoin(AnalysisParam::model()->tableName().' ap', 'ap.id = att.analysis_param_id')
                ->order('at.name asc')
                ->queryAll();

            return $analysistypetemplate;

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    */
}

?>
