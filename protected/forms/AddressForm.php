<?php

class AddressForm extends FormModel {

	public $id;
	public $street_name;
	public $house_number;
	public $flat_number;
	public $post_index;
	public $region_name;
	public $district_name;

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
			[ [ "region_name", "district_name", "street_name" ], "length", "max" => 100 ],
			[ [ "house_number", "flat_number" ], "length", "max" => 10 ],
		];
	}

	/**
	 * Override that method to return config. Config should return array associated with
	 * model's variables. Every field must contains 3 parameters:
	 *  + label - Variable's label, will be displayed in the form
	 *  + type - Input type (@see _LFormInternalRender#render())
	 *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
	 * @return Array - Model's config
	 */
	public function config() {
		return [
			"id" => [
				"label" => "Идентификатор",
				"type" => "number",
				"rules" => "numerical"
			],
			"region_name" => [
				"label" => "Регион",
				"type" => "text"
			],
			"district_name" => [
				"label" => "Район",
				"type" => "text"
			],
			"street_name" => [
				"label" => "Название улицы",
				"type" => "text"
			],
			"house_number" => [
				"label" => "Номер дома",
				"type" => "text"
			],
			"flat_number" => [
				"label" => "Номер квартиры",
				"type" => "text"
			],
			"post_index" => [
				"label" => "Почтовый индекс",
				"type" => "text"
			]
		];
	}
}