<?php

class m150318_020002_laboratory_direction_repeat extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."direction" ADD "is_repeated" INT DEFAULT 0
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."direction" DROP "is_repeated"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}