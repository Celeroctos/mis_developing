<?php

class Flipper extends Widget {

	/**
	 * @var string - Flipper's identification
	 * 	number
	 */
	public $id = null;

	/**
	 * @var string - Content on the front of
	 * 	widget
	 */
	public $front = "";

	/**
	 * @var string - Content on the back of
	 * 	widget
	 */
	public $back = "";

	/**
	 * @var string - Default animation velocity
	 */
	public $velocity = "0.6s";

	/**
	 * @var string - Component's perspective depth
	 */
	public $perspective = "1000px";

	/**
	 * @var string - Component's width
	 */
	public $width = "100%";

	/**
	 * @var string - Component's height
	 */
	public $height = "auto";
}