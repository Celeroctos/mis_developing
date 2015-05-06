<?php

class DirectionPanel extends Panel {

	/**
	 * @var string - Default panel's date
	 */
	public $date = null;

	public $controlsWrapperClass = "col-xs-6 text-right no-padding";
	public $titleWrapperClass = "col-xs-6 text-left no-padding";

	/**
	 * @var int - Default panel's control mode is button, don't
	 * 	change it, cuz it uses inline button elements
	 * @internal
	 */
	public $controlMode = ControlMenu::MODE_BUTTON;

	/**
	 * @var int - Direction status (for extra menu)
	 */
	public $status = LDirection::STATUS_TREATMENT_ROOM;

	public $controls = [
		"panel-search-button" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-search",
			"label" => "Поиск"
		],
	];

	public function init() {
		if ($this->status == LDirection::STATUS_TREATMENT_ROOM) {
			$this->controls += [
				"panel-date-button" => [
					"class" => "btn btn-default",
					"icon" => "glyphicon glyphicon-calendar",
					"label" => "Дата&nbsp;(" . CHtml::tag("span", [
							"class" => "direction-date"
						], date("Y-m-d")) . ")",
				],
				"panel-update-button" => [
					"class" => "btn btn-default",
					"icon" => "glyphicon glyphicon-refresh",
					"onclick" => "$(this).panel('update')",
					"label" => "Сегодня",
				]
			];
		} else {
			$this->controls += [
				"panel-update-button" => [
					"class" => "btn btn-default",
					"icon" => "glyphicon glyphicon-refresh",
					"onclick" => "$(this).panel('update')",
					"label" => "Обновить",
				]
			];
		}
		parent::init();
	}
}