<?php

class Laboratory_Widget_DirectionCreator extends Widget {

	/**
	 * @var array - Array with extra controls
	 * 	to avoid [buttons] override
	 */
	public $disableControls = false;

	/**
	 * @var array - Set default form values
	 * @see AutoForm::defaults
	 */
	public $defaults = [];

	/**
	 * @var string - Relative href for direction register
	 * 	form
	 */
	public $url = "laboratory/direction/register";

	/**
	 * Run widget to render it's content
	 */
	public function run() {
        $this->render("Laboratory_Widget_DirectionCreator");
    }
} 