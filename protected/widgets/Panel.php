<?php

class Panel extends Widget {

	/**
	 * @var string - Panel's primary key, by default it
	 * 	generates automatically
	 */
    public $id = null;

	/**
	 * @var string - Panel's title, which displays
	 *	 in panel's heading
	 */
	public $title = "";

	/**
	 * @var string|null|Widget - Body content, if null, then content
	 *	will be obtained from print stream
	 */
	public $body = null;

	/**
	 * @var string - Default panel style
	 */
	public $panelClass = "panel panel-default";

	/**
	 * @var string - Style of panel's heading, by
	 * 	default it uses row, cuz it has hidden glyphicons
	 * 	in [col-xs-12] classes, which needs fixes height
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
	public $titleWrapperClass = "col-xs-10 text-left no-padding";

	/**
	 * @var string - Classes for control container which
	 * 	separated after title container
	 */
	public $controlsWrapperClass = "col-xs-2 text-right no-padding";

	/**
	 * @var string - Classes for panel's title, which separated
	 * 	in panel's heading and wrapped by it's wrapper
	 */
	public $titleClass = "panel-title";

	/**
	 * @var bool - Should panel be collapsible with
	 * 	collapse/expand button
	 */
    public $collapsible = null;

	/**
	 * @var bool - Should panel be upgradable with
	 *	refresh button
	 */
	public $upgradeable = true;

	public $controls = [
		"panel-update-button" => [
			"class" => "glyphicon glyphicon-refresh text-center",
			"onclick" => "$(this).panel('update')"
		],
		"panel-collapse-button" => [
			"class" => "glyphicon glyphicon-chevron-up text-center",
			"onclick" => "$(this).panel('toggle')"
		]
	];

	/**
	 * Initialize widget
	 */
    public function init() {
        if ($this->body instanceof Widget && !empty($this->body)) {
            $this->body = $this->body->call();
        }
		if (empty($this->id)) {
			$this->id = UniqueGenerator::generate("panel");
		}
		if ($this->body == null) {
			ob_start();
		}
    }

	/**
	 * Run widget
	 */
    public function run() {
		$this->render(__CLASS__, [
			"content" => $this->body ? $this->body : ob_get_clean()
		]);
    }

	/**
	 * Render panel's control elements
	 */
	public function renderControls() {
		foreach ($this->controls as $class => $options) {
			if (!isset($options["class"])) {
				$options["class"] = "panel-control-button $class";
			} else {
				$options["class"] .= " panel-control-button $class";
			}
			print CHtml::tag("span", $options, "");
		}
	}
} 