<?php

abstract class GActiveRecord extends ActiveRecord {

	/**
	 * @var int - How many items per page should be displayed?
	 */
	public $itemsPerPage = 20;

	/**
	 * @var bool - Default order method
	 */
	public $orderDirection = CSort::SORT_ASC;

	/**
	 * Override that method to return form model instance, it will
	 * help search method to implement automatic search, you can
	 * use [createFilter] method or override [getKeys] to filter
	 * displayable attributes
	 *
	 * @return FormModel - Form model instance for current table model
	 *
	 * @see FormModel::createFilter - For backward method, it will ignore hidden fields
	 * @see FormModel::backward - Same as [CFormModel::rules], but with 'hide' property
	 */
	public abstract function getForm();

	/**
	 * Override that method to return your own list of
	 * displayable attributes
	 *
	 * @return array - Array with names (key) of attributes to display
	 */
	public function getKeys() {
		return [];
	}

	/**
	 * Override that method to return attributes to sort, by default
	 * it returns full list with attributes from [getKeys] method
	 *
	 * @return array - Array with names (key) of attributes to sort
	 */
	public function getSort() {
		return $this->getKeys();
	}

	/**
	 * Override that method to realize your own implementation of
	 * search method with your own features
	 *
	 * @return CActiveDataProvider - Active data provider instance
	 */
	public function search() {
		$criteria = new CDbCriteria();
		foreach ($this->getKeys() as $key) {
			if (isset($config["type"])) {
				$type = strtolower($config["type"]);
				if ($type == "text" || $type == "textarea") {
					$criteria->compare($key, $this->$key, true);
				}
			} else {
				$criteria->compare($key, $this->$key, false);
			}
		}
		return new ActiveDataProvider($this, [
			"form" => $this->getForm(),
			"pagination" => [
				"pageSize" => $this->itemsPerPage
			],
			"criteria" => $criteria,
			"sort" => [
				"attributes" => $this->getSort(),
				"defaultOrder" => [
					"name" => $this->orderDirection,
				],
			],
		]);
	}
}