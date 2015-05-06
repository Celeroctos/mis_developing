<?php

class CheckboxPanel extends Panel {

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
			$this->name = $this->getId();
		}
		$this->title = CHtml::checkBox("PanelForm[{$this->name}]", $this->checked, [
				"class" => "panel-title-checkbox",
				"id" => $this->name."-label",
				"onchange" => "$(this).panel('toggle')"
			])."&nbsp;".CHtml::label($this->title, $this->name."-label");
		parent::init();
	}
}