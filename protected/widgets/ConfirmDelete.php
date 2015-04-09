<?php

class ConfirmDelete extends Modal {

	/**
	 * @var string - Default title
	 */
	public $title = "Подтвердите удаление";

	/**
	 * @var string - Default identification value
	 */
	public $id = "confirm-delete-modal";

	/**
	 * @var string - Displayable text in modal body
	 */
	public $body = "<p>Вы уверены, что хотите удалить этот элемент? Результат этого действия нельзя будет отменить</p>";

	/**
	 * @var array - Array with action buttons
	 */
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

	/**
	 * @var string - Small body class
	 */
	public $class = "modal-sm";

	/**
	 * @var bool - Disable fade effect
	 */
	public $fade = false;
}