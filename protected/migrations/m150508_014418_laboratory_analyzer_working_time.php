<?php

class m150508_014418_laboratory_analyzer_working_time extends CDbMigration
{
	public function safeUp() {
		$this->execute("ALTER TABLE lis.analyzer ADD working_time INT DEFAULT 0");
	}

	public function safeDown() {
		$this->execute("ALTER TABLE lis.analyzer DROP working_time");
	}
}