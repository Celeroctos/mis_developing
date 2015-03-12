<?php

/**
 * This is the model class for table "lis.machine".
 *
 * The followings are the available columns in table 'lis.machine':
 * @property integer $id
 * @property string $name
 * @property integer $serial
 * @property string $model
 * @property string $software_version
 * @property integer $analyzer_type_id
 *
 * The followings are the available model relations:
 * @property AnalyzerTypes $analyzerType
 */
class Machine extends MisActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lis.machine';
	}

    public function rules()
    {
        return [
            ['name', 'required', 'on'=>'machines.update'],
            ['name, model, software_version', 'type', 'type'=>'string', 'on'=>'machines.update'],
            ['id, serial, analyzer_type_id', 'type', 'type'=>'integer', 'on'=>'machines.update'], //[controller].[action]

            ['name', 'required', 'on'=>'machines.create'],
            ['name, model, software_version', 'type', 'type'=>'string', 'on'=>'machines.create'],
            ['id, serial, analyzer_type_id', 'type', 'type'=>'integer', 'on'=>'machines.create'], //[controller].[action]

            ['id, name, serial, model, software_version, analyzer_type_id', 'safe', 'on'=>'machines.search'],
        ];
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'analyzerType' => array(self::BELONGS_TO, 'AnalyzerTypes', 'analyzer_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название аналтзатора',
			'serial' => 'Номер',
			'model' => 'Модель анализатора',
			'software_version' => 'Версия ПО',
			'analyzer_type_id' => 'Тип анализатора',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('serial',$this->serial);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('software_version',$this->software_version,true);
		$criteria->compare('analyzer_type_id',$this->analyzer_type_id);
        $qq =  new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>[
                'attributes'=>[
                    'id', 
                    'name', 
                    'serial', 
                    'model',
                    'software_version',
                    'analyzer_type_id'],
                'defaultOrder'=>[
                    'name'=>CSort::SORT_ASC,
                ],
            ],
        ));
		return $qq;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Machine the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getAnalyzer_Type($analyzertype_id) {
        $analyzertype = AnalyzerType::getOne($analyzertype_id);
        return $analyzertype['type'];
    }
}
