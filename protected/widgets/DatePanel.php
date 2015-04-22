<?php

class DatePanel extends Panel {

	/**
	 * @var string - Default panel's date
	 */
	public $date = null;

	public $titleWrapperClass = "col-xs-6 text-left no-padding";
	public $controlsWrapperClass = "col-xs-6 text-right no-padding";

	public function init() {
		$this->controls = [
			"panel-date-button" => [
				"class" => "btn btn-default btn",
				"label" => "<span class=\"glyphicon glyphicon-calendar\"></span>&nbsp;&nbsp;Дата&nbsp;(" . CHtml::tag("span", [
						"class" => "direction-date"
					], date("Y-m-d")) . ")",
			],
			"panel-update-button" => [
				"class" => "btn btn-default btn",
				"label" => "<span class=\"glyphicon glyphicon-refresh\"></span>&nbsp;&nbsp;Обновить",
				"onclick" => "$(this).panel('update')"
			]
		];
		parent::init();
	}
}