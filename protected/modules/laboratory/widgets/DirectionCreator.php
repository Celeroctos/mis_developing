<?php

class DirectionCreator extends AutoForm {

	public $buttons = [
		"direction-creator-register" => [
			"label" => "Сохранить",
			"class" => "btn btn-primary",
			"type" => "button"
		],
	];

	/**
	 * @var array - Array with extra controls
	 * 	to avoid [buttons] override
	 */
	public $controls = [
		"direction-creator-cancel" => [
			"label" => "Отменить",
			"class" => "btn btn-default",
			"type" => "button",
			"style" => "margin-left: 10px"
		],
	];

	public $divide = true;

	public function run() {
		if (empty($this->model)) {
			$this->model = new LDirectionForm("treatment");
		}
		if (empty($this->url)) {
			$this->url = Yii::app()->getUrlManager()->createUrl("laboratory/direction/register");
		}
		$this->buttons += $this->controls;
        parent::run();
    }
} 