<?php

class CheckPanel extends Panel {

	/**
	 * @var string name of current panel in your
	 * 	parent form component
	 */
	public $name = null;

	/**
	 * @var bool is panel should be checked or not, checked
	 * 	panel renders expanded and not checked collapsed
	 */
	public $checked = true;

	public function init() {
		if (empty($this->name)) {
			$this->name = "PanelForm[". $this->getId() ."]";
		}
		$this->collapsible = false;
		$this->collapsed = !$this->checked;
		$this->title = CHtml::checkBox($this->name, $this->checked, [
				"class" => "panel-title-checkbox",
				"name" => $this->name,
				"id" => $this->getId()."-label",
				"onchange" => "$(this).panel($(this).prop('checked') ? 'expand' : 'collapse')"
			]).CHtml::label($this->title, $this->getId()."-label");
		parent::init();
	}
}