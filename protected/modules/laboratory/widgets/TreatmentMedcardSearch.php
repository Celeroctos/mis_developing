<?php

class TreatmentMedcardSearch extends MedcardSearch {

	public $tableConfig = [
		"controls" => [
			"direction-register-icon" => [
				"class" => "glyphicon glyphicon-link",
				"tooltip" => "Создать направление"
			],
			"medcard-show-icon" => [
				"class" => "glyphicon glyphicon-list",
				"tooltip" => "Открыть / Изменить медкарту"
			]
		],
		"primaryKey" => "medcard_id",
		"click" => null,
	];

	public $id = "treatment-medcard-table";

	public function init() {
		$this->widget("Modal", [
			"title" => "Регистрация направления",
			"body" => $this->createWidget("AutoForm", [
				"url" => Yii::app()->getUrlManager()->createUrl("laboratory/direction/register"),
				"model" => new LDirectionForm("treatment"),
				"id" => "register-direction-form"
			]),
			"buttons" => [
				"buttons" => [
					"text" => "Сохранить",
					"class" => "btn btn-primary",
					"type" => "button",
					"attributes" => [
						"onclick" => "$('#register-direction-form').form('send', function(status) { if (status) $(this).parents('.modal').modal('hide'); })"
					]
				],
			],
			"id" => "register-direction-modal"
		]);
		$this->widget("Modal", [
			"title" => "Медицинская карта",
			"body" => "<h4 class=\"text-center no-margin\">Медкарта не выбрана</h4>",
			"buttons" => [
			],
			"id" => "show-medcard-modal"
		]);
		parent::init();
	}
}