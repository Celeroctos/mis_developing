<?php

class LMedcardSearchForm extends FormModel {

	public $card_number;
	public $fio;
	public $enterprise_id;
	public $phone;

    /**
     * Override that method to return config. Config should return array associated with
     * model's variables. Every field must contains 3 parameters:
     *  + label - Variable's label, will be displayed in the form
     *  + type - Input type (@see _LFormInternalRender#render())
     *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
     * @return Array - ActiveRecord's config
     */
    public function config() {
        return [
            "card_number" => [
                "label" => "Номер ЛКП",
                "type" => "text"
            ],
            "enterprise_id" => [
                "label" => "Направитель",
                "type" => "DropDown",
				"table" => [
					"name" => "mis.enterprise_params",
					"key" => "id",
					"value" => "shortname"
				]
            ],
			"fio" => [
				"label" => "ФИО",
				"type" => "text"
			],
            "phone" => [
                "label" => "Телефон",
                "type" => "Phone",
                "rules" => "LPhoneValidator"
            ]
        ];
    }
}