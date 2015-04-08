<?php

class ConfirmDelete extends Modal {

	public $title = "Подтвердите удаление";

	public $id = "confirm-delete-modal";

	public $body = "";

	public $buttons = [
		"confirm-delete-button" => [
			"text" => "Удалить",
			"class" => "btn btn-danger",
			"options" => [
				"data-dismiss" => "modal"
			],
			"type" => "button"
		]
	];

	public $class = "modal-sm";

	public $fade = false;
}