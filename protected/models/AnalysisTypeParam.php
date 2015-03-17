<?php
/**
 * Класс для работы с отделениями
 */
class AnalysisTypeParam extends MisActiveRecord 
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
			'analysisTypes' => array(self::BELONGS_TO, 'AnalysisType', 'analysis_type_id'),
            'analysisParams' => array(self::BELONGS_TO, 'AnalysisParam', 'analysis_param_id'),
		];
	}
	
    public function rules()
    {
        return array(
            array('analysis_type_id, analysis_param_id', 'required'),
            array('analysis_type_id, analysis_param_id, is_default, seq_number', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, analysis_type_id, analysis_param_id, is_default, seq_number', 'safe', 'on'=>'search'),
        );
    }

    public function tableName()
    {
        return 'lis.analysis_type_params';
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
}

?>
