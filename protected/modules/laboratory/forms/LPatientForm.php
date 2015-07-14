<?php

class LPatientForm extends FormModel {

	public $id;
	public $surname;
	public $name;
	public $patronymic;
	public $sex;
	public $birthday;
	public $passport_id;
	public $policy_id;
	public $register_address_id;
	public $address_id;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ "id", "hide", "on" => "treatment.edit" ]
		];
	}

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
			"id" => [
				"label" => "Идентификатор",
				"type" => "hidden",
				"rules" => "numerical"
			],
			"surname" => [
				"label" => "Фамилия",
				"type" => "text",
				"rules" => "required"
			],
			"name" => [
				"label" => "Имя",
				"type" => "text",
				"rules" => "required"
			],
			"patronymic" => [
				"label" => "Отчество",
				"type" => "text",
				"rules" => "safe"
			],
			"sex" => [
				"label" => "Пол",
				"type" => "sex",
				"rules" => "required"
			],
			"birthday" => [
				"label" => "Дата рождения",
				"type" => "date",
				"rules" => "required"
			],
			"passport_id" => [
				"label" => "Пасспорт",
				"type" => "hidden"
			],
			"policy_id" => [
				"label" => "Полис",
				"type" => "hidden"
			],
			"register_address_id" => [
				"label" => "Адрес регистрации",
				"type" => "address",
				"rules" => "safe",
				"table" => [
					"name" => "lis.address",
					"format" => "р. %{region_name}, район. %{district_name}, ул. %{street_name}, д. %{house_number}, кв. %{flat_number}",
					"key" => "id",
					"value" => "street_name, house_number, flat_number, region_name, district_name"
				]
			],
			"address_id" => [
				"label" => "Адрес проживания",
				"type" => "address",
				"rules" => "safe"
			]
		];
	}
}