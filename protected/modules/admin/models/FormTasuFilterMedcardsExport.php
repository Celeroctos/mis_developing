<?php

class FormTasuFilterMedcardsExport extends CFormModel
{
    public $dateFrom;
    public $dateTo;
    public $medcard;

    public function rules()
    {
        return array(
            array(
                'dateFrom, dateTo, medcard', 'safe'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'dateFrom' => 'С',
            'dateTo' => 'По',
            'medcard' => 'Номер карты'
        );
    }
}


?>