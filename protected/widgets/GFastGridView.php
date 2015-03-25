<?php

class GFastGridView extends Widget {

	/**
	 * @var GActiveRecord - Active record instance with search method
	 */
	public $model;

	/**
	 * @var
	 */
	public $url;

	/**
	 * Run widget
	 * @throws CException
	 */
	public function run() {
		$columns = [];
		if (!$this->model instanceof GActiveRecord) {
			throw new CException("Model with be an instance of GActiveRecord class, found \"". get_class($this->model) ."\"");
		}
		$form = $this->model->form;
		foreach ($form->getConfig() as $key => $config) {
			if ($config != null) {
				$label = $config["label"];
			} else {
				$label = $key;
			}
			$columns[] = [
				"header" => $label,
				"name" => $key,
				"type" => "raw",
			];
		}
		if (strrpos($this->url, "/") !== strlen($this->url)) {
//			$this->url .= "/";
		}
		$this->render(__CLASS__, [
			"columns" => $columns
		]);
	}
}