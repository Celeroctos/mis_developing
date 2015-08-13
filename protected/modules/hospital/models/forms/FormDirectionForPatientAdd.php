<?php
class FormDirectionForPatientAdd extends FormMisDefault
{
    public $id;
    public $type;
    public $isPregnant;
    public $wardId;
    public $omsId;
    public $doctorId;
    public $pregnantTerm;
    public $cardNumber;
    public $doctorDestId;
    public $enterpriseId;
    public $writeType;

    public function rules()
    {
        return array(
        /*
            array(
                'type, isPregnant, wardId, omsId, doctorId, pregnantTerm, cardNumber', 'required'
            ),
        */
            array(
                'id', 'safe'
            ),
            array(
                'id, omsId, doctorId, pregnantTerm','numerical'
            ),
            array(
            	'type, isPregnant, wardId, omsId, doctorId, pregnantTerm, cardNumber','safe'
            ),
            array(
            	'enterpriseId','numerical'
            ),
            array(
            	'doctorDestId','numerical'
            ),
            array(
            	'writeType','numerical'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'type' => 'Тип госпитализации',
            'isPregnant' => 'Беременная',
            'pregnantTerm' => 'Срок беременности (недель)',
        	'enterpriseId'=>'Учреждение',
        	'wardId' => 'Отделение',
        	'doctorId'=>'Врач',
        	'doctorDestId'=>'Врач'
        );
    }
}


?>