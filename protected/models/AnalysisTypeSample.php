<?php

/**
 * This is the model class for table "lis.analysis_types_samples".
 *
 * The followings are the available columns in table 'lis.analysis_types_samples':
 * @property integer $id
 * @property integer $analysis_type_id
 * @property integer $analysis_sample_id
 *
 * The followings are the available model relations:
 * @property AnalysisTypes $analysisType
 * @property AnalysisSamples $analysisSample
 */
class AnalysisTypeSample extends MisActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'lis.analysis_type_samples';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('analysis_type_id, analysis_sample_id', 'required'),
            array('analysis_type_id, analysis_sample_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, analysis_type_id, analysis_sample_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'analysisTypes' => array(self::BELONGS_TO, 'AnalysisType', 'analysis_type_id'),
            'analysisSamples' => array(self::BELONGS_TO, 'AnalysisSampleType', 'analysis_sample_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'analysis_type_id' => 'Тип анализа',
            'analysis_sample_id' => 'Тип образца',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('analysis_type_id', $this->analysis_type_id);
        $criteria->compare('analysis_sample_id', $this->analysis_sample_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AnalysisTypeSamples the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
