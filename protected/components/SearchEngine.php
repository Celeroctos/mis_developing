<?php

class SearchEngine extends CComponent {

	public static function createWithConfig($model, $config = []) {
		$engine = new static($model);
		foreach ($config as $key => $value) {
			$engine->$key = $value;
		}
		return $engine;
	}

	private function __construct($model) {
		$this->_model = $model;
	}

	private $_model;
}