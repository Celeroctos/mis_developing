<?php

class LAddress extends ActiveRecord {

	/**
	 * Returns the name of the associated database table.
	 * By default this method returns the class name as the table name.
	 * You may override this method if the table is not named after this convention.
	 * @return string the table name
	 */
	public function tableName() {
		return "lis.address";
	}
}