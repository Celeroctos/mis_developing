<?php

class m150408_230053_laboratory_address_fix extends CDbMigration {

	public function safeUp() {
		$this->execute("ALTER TABLE lis.address ADD string TEXT DEFAULT NULL");
	}

	public function safeDown() {
		$this->execute("ALTER TABLE lis.address DROP string");
	}
}