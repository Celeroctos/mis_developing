<?php
class FormPatientAdd extends FormMisDefault
{
    public $policy;
    public $lastName;
    public $firstName;
    public $middleName;
    public $gender;
    public $birthday;
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

    public function rules()
    {
		Yii::import('ext.validators.SNILSValidator');
		Yii::import('ext.validators.SerialNumberValidator');
		Yii::import('ext.validators.NameValidator');
		Yii::import('ext.validators.FamilyValidator');
		Yii::import('ext.validators.FathersNameValidator');
        return array(
            array(
                'policy, lastName, firstName, gender, birthday, doctype, serie, docnumber, documentGivedate, addressReg, address, contact, whoGived', 'required'
            ),
            array(
                'workPlace, workAddress, post, snils, invalidGroup, middleName', 'safe'
            ),
			array(
				'snils', 'SNILSValidator'
				),
			array(
				'serie,docnumber', 'SerialNumberValidator'
			),
			array(
				'lastName', 'FamilyValidator'
			),
		    array(
				'firstName', 'NameValidator'
			),
			array(
				'middleName', 'FathersNameValidator'
			)
        );
    }

    public function attributeLabels()
    {
        return array(
            'policy' => 'ОМС',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'gender' => 'Пол',
            'birthday' => 'Дата рождения',
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
            'invalidGroup' => 'Группа инвалидности'
        );
    }
}


?>