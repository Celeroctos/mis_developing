<?php

class Laboratory_Widget_MedcardSearch extends Widget {

	/**
	 * @var string - Medcard grid provider
	 */
	public $provider = "Laboratory_Grid_Medcard";

	/**
	 * @var array - Default configuration for table provider
	 */
	public $config = [];

	/**
	 * Run widget to return just rendered content
	 * @throws CException
	 */
    public function run() {
        return $this->render(__CLASS__);
    }
} 