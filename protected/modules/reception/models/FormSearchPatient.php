<?php

class FormSearchPatient extends CFormModel
{
    public $enterpriseId;

    public function rules()
    {
        return array(
            array(
                'enterpriseId', 'numerical'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'enterpriseId' => 'Учреждение',
        );
    }
}


?>