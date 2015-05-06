<?php

class Panel extends Widget {

	/**
	 * Constants for panel's wrapper class
	 */
	const PANEL_CLASS_DEFAULT = "panel panel-default";
	const PANEL_CLASS_PRIMARY = "panel panel-primary";
	const PANEL_CLASS_SUCCESS = "panel panel-success";
	const PANEL_CLASS_DANGER = "panel panel-danger";
	const PANEL_CLASS_WARNING = "panel panel-warning";

	/**
	 * @var string - Panel's primary key, by default it
	 * 	generates automatically
	 * @see UniqueGenerator::generate
	 */
    public $id = null;

	/**
	 * @var string - Panel's title, which displays
	 *	 in panel's heading
	 */
	public $title = "";

	/**
	 * @var string|null|Widget - Body content, if null, then content
	 *	obtains from print stream
	 */
	public $body = null;

	/**
	 * @var string - Default panel style
	 */
	public $panelClass = self::PANEL_CLASS_DEFAULT;

	/**
	 * @var string - Style of panel's heading, by default it uses row, cuz it has
	 * 	hidden glyphicons in [col-xs-12] wrapper, which needs fixed height
	 */
	public $headingClass = "panel-heading row no-margin";

	/**
	 * @var string - Style of panel's body, you can
	 * 	add [no-padding] style to remove panel's body padding
	 */
	public $bodyClass = "panel-body";

	/**
	 * @var string - Classes for panel's content block, which
	 * 	separated in body container {.panel-body > .row > .panel-content}
	 */
	public $contentClass = "col-xs-12 no-padding no-margin panel-content";

	/**
	 * @var string - Classes for heading's title
	 * 	div container
	 */
	public $titleWrapperClass = "col-xs-9 text-left no-padding";

	/**
	 * @var string - Classes for control container which
	 * 	separated after title container
	 */
	public $controlsWrapperClass = "col-xs-3 text-right no-padding";

	/**
	 * @var string - Classes for panel's title, which separated
	 * 	in panel's heading and wrapped by it's wrapper
	 */
	public $titleClass = "panel-title";

	/**
	 * @var bool - Should panel be collapsible with
	 * 	collapse/expand button, it don't take any effect
	 * 	if [controlModel] sets to CONTROL_MODE_ICON
	 * @see controlMode
	 */
    public $collapsible = false;

	/**
	 * @var bool - Should panel be upgradable with refresh button, it
	 * 	will take any effects only if [body] is widget object, which
	 * 	has bee created via [@see Widget::createWidget] method
	 * @see body
	 * @see Widget::createWidget
	 */
	public $upgradeable = null;

	/**
	 * @var array - Array with control elements, it's attributes depends on
	 * 	control display mode. You should always use [icon] and [label] attributes
	 * 	cuz every control mode must support that attributes. Control parameters
	 * 	is HTML attributes that moves to it's tag (tag name depends on control
	 * 	display mode).
	 * @see ControlMenu
	 * @see controlMode
	 */
	public $controls = [
		"panel-update-button" => [
			"icon" => "glyphicon glyphicon-refresh",
			"onclick" => "$(this).panel('update')",
			"label" => "Обновить",
			"class" => "btn btn-default"
		],
		"panel-collapse-button" => [
			"icon" => "glyphicon glyphicon-asterisk",
			"label" => "Свернуть/Развернуть",
			"onclick" => "$(this).panel('toggle')",
			"class" => "btn btn-default"
		]
	];

	/**
	 * @var int - How to display control elements, set it
	 * 	to CONTROL_MODE_NONE to disable control elements
	 * @see ControlMenu
	 */
	public $controlMode = ControlMenu::MODE_BUTTON;

	/**
	 * @var string - String with serialized parameters
	 * @internal
	 */
	public $attributes = null;

	/**
	 * Initialize widget
	 */
    public function init() {
        if ($this->body instanceof Widget) {
			$this->_widget = get_class($this->body);
			$this->attributes = $this->body->getSerializedAttributes();
            $this->body = $this->body->call();
        } else {
			if ($this->upgradeable === null) {
				$this->upgradeable = false;
			}
			$this->_widget = null;
		}
		if (empty($this->id)) {
			$this->id = UniqueGenerator::generate("panel");
		}
		if ($this->body == null) {
			ob_start();
		}
		if ($this->collapsible == false || true) {
			unset($this->controls["panel-collapse-button"]);
		}
		if ($this->collapsible == true) {
			$this->titleClass .= " panel-title-collapsible";
		}
		if ($this->upgradeable === false) {
			unset($this->controls["panel-update-button"]);
		}
    }

	/**
	 * Run widget
	 */
    public function run() {
		$this->render("application.widgets.views.Panel", [
			"content" => $this->body ? $this->body : ob_get_clean(),
			"parameters" => $this->attributes,
			"widget" => $this->_widget,
		]);
    }

	public function renderControls() {
		$this->widget("ControlMenu", [
			"controls" => $this->controls,
			"mode" => $this->controlMode
		]);
	}

	private $_widget;
} 