<?php

class Laboratory_Widget_DirectionSearch extends Widget {

	/**
	 * @var string name of table widget
	 */
	public $widget = null;

	/**
	 * @var array with table provider configuration
	 */
	public $config = [];

	/**
	 * @var string|ActiveDataProvider instance or name of
	 * 	class with data provider
	 */
	public $provider = null;

	public function run() {
		return $this->render('Laboratory_Widget_DirectionSearch');
	}
}