<?php

class GGridView extends Widget {

	/**
	 * @var GActiveRecord - Active record instance for guides
	 */
	public $model = null;

	/**
	 * @var string - Displayable title (guide name)
	 */
	public $title = null;

	/**
	 * @var string - Path to controller (without action)
	 */
	public $url = null;

	/**
	 * @var array - Default form actions
	 */
	public $actions = [
		"create" => "create",
		"update" => "update",
		"delete" => "delete"
	];

	/**
	 * Run widget
	 */
	public function run() {
		$columns = [];
		if (!$this->model instanceof GActiveRecord) {
			throw new CException("Model with be an instance of GActiveRecord class, found \"". get_class($this->model) ."\"");
		}
		$form = $this->model->getForm();
		foreach ($form->getConfig() as $key => $config) {
			if ($config != null) {
				$label = $config["label"];
			} else {
				$label = $key;
			}
			$columns[] = [
				"header" => $label,
				"name" => $key,
			];
		}
		if (strrpos($this->url, "/") !== strlen($this->url)) {
			$this->url .= "/";
		}
		$this->render(__CLASS__, [
			"columns" => $columns
		]);
	}
}