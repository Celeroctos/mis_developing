<?php

class m150429_052224_laboratory_direction_time_fix extends CDbMigration
{
	public function safeUp() {
		$this->execute("UPDATE lis.direction SET sending_date = registration_time WHERE sending_date IS NULL");
	}

	public function safeDown() {
	}
}