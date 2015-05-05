<?php

class LDirectionParameter extends ActiveRecord {

	public $direction_id;
	public $analysis_type_parameter_id;
	public $checked;

	public function tableName() {
		return "lis.direction_parameter";
	}
}