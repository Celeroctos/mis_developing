<?php

class GuideValue extends ActiveRecord {

	public $id;
	public $guide_row_id;
	public $guide_column_id;
	public $value;

	/**
	 * Returns the name of the associated database table.
	 * By default this method returns the class name as the table name.
	 * You may override this method if the table is not named after this convention.
	 * @return string the table name
	 */
	public function tableName() {
		return "lis.guide_value";
	}
}