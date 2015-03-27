<?php

abstract class GActiveRecord extends ActiveRecord {

	/**
	 * @param string $scenario
	 * @throws CException
	 */
	public function __construct($scenario = 'search') {
		if (!($this->form = $this->getForm()) instanceof FormModel) {
			throw new CException("Active record's form must be an instance of FormModel, and mustn't be null");
		}
		return parent::__construct($scenario);
	}

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
	 * @var FormModel - Form model instance fetch via [getForm] method
	 */
	public $form;

	/**
	 * Override that method to return your own list of
	 * displayable attributes
	 *
	 * @return array - Array with names (key) of attributes to display
	 */
	public function getKeys() {
		return array_keys($this->getForm()->getConfig());
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
		foreach ($this->form->getConfig() as $key => $config) {
			if ($this->hasAttribute($key)) {
				$criteria->compare($key, $this->getAttribute($key));
			}
		}
		return new ActiveDataProvider($this, [
			"sort" => [
				"defaultOrder" => "id asc"
			],
			"pagination" => [
				"pageSize" => $this->getItemsPerPage()
			],
			"form" => $this->form,
		]);
	}

	/**
	 * Override that method to return count of displayable
	 * rows in one page
	 * @return int - Count of displayable rows
	 */
	public function getItemsPerPage() {
		return 20;
	}

	/**
	 * Override that method to return default order direction
	 * @return bool - True for acs order and false for desc
	 */
	public function getOrderDirection() {
		return CSort::SORT_ASC;
	}
}