<?php

class FormElementAdd extends CFormModel
{
    public $type;
    public $categorieId;
    public $label;
    public $guideId;
    public $id;

    public function rules()
    {
        return array(
            array(
                'type, categorieId, label', 'required'
            ),
            array(
                'id, guideId', 'safe'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'type' => 'Тип элемента',
            'categorieId' => 'Категория',
            'label' => 'Метка рядом с элементом',
            'guideId' => 'Справочник'
        );
    }
}


?>