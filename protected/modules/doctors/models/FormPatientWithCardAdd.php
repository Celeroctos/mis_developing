<?php

class FormPatientWithCardAdd extends FormMisDefault
{
    public $doctype;
    public $serie;
    public $docnumber;
    public $whoGived;
    public $documentGivedate;
    public $addressReg;
    public $address;
    public $workPlace;
    public $workAddress;
    public $post;
    public $contact;
    public $snils;
    public $invalidGroup;
    public $policy;
    public $cardNumber;
    public $privilege;
    public $privDocname;
    public $privDocnumber;
    public $privDocserie;
    public $privDocGivedate;
    public $profession;
    public $mediateId; // Опосредованный пациент может быть

    public function rules()
    {
        Yii::import('ext.validators.SNILSValidator');
        Yii::import('ext.validators.SerialNumberValidator');
        return array(
            array(
                'doctype, serie, docnumber, documentGivedate, addressReg, address, contact, whoGived, privilege', 'required'
            ),
            array(
                'workPlace, workAddress, post, snils, invalidGroup, policy, cardNumber, privDocname, privDocnumber, privDocserie, privDocGivedate, profession, mediateId', 'safe'
            ),
            array(
                'snils', 'SNILSValidator'
            ),
            array(
                'serie, docnumber', 'SerialNumberValidator'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'doctype' => 'Тип документа',
            'serie' => 'Серия',
            'docnumber' => 'Номер',
            'whoGived' => 'Кем выдан',
            'documentGivedate' => 'Дата выдачи',
            'addressReg' => 'Адрес регистрации',
            'address' => 'Адрес проживания',
            'workPlace' => 'Место работы',
            'workAddress' => 'Адрес работы',
            'post' => 'Должность',
            'contact' => 'Контактные данные',
            'snils' => 'СНИЛС',
            'invalidGroup' => 'Группа инвалидности',
            'privilege' => 'Льгота',
            'privDocname' => 'Название документа',
            'privDocnumber' => 'Номер',
            'privDocserie' => 'Серия, номер',
            'privDocGivedate' => 'Дата выдачи',
            'profession' => 'Профессия'
        );
    }
}


?>