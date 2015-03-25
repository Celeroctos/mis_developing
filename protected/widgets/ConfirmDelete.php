<?php

class ConfirmDelete extends Widget {

	public $title = "Подтвердите удаление";
	public $id = "confirm-delete-modal";

	public function run() {
		$this->render(__CLASS__, null);
	}
}