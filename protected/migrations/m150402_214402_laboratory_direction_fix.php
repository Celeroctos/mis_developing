<?php

class m150402_214402_laboratory_direction_fix extends CDbMigration {

	public function safeUp() {
		$this->execute("ALTER TABLE lis.direction DROP is_repeated");
	}

	public function safeDown() {
		$this->execute("ALTER TABLE lis.direction ADD is_repeated INT DEFAULT 0");
	}
}