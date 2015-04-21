<?php

class m150421_201438_laboratory_direction_register_date extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."direction" ADD "registration_time" TIMESTAMP DEFAULT now()::timestamp(0)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."direction" DROP "registration_time"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}