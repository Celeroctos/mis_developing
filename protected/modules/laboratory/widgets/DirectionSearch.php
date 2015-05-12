<?php

class DirectionSearch extends Widget {

	/**
	 * @var string name of table widget
	 */
	public $widget = null;

	public function run() {
		return $this->render("DirectionSearch");
	}
}