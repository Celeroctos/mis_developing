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
		"load" => "load",
		"delete" => "delete"
	];

	/**
	 * @var string - Extra content to render, it will be added to
	 * 	modal windows ('create' and 'update') after main form, need
	 * 	for many to many references
	 */
	public $content = "";

	/**
	 * Run widget
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