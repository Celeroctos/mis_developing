<?php

class m150415_210656_laboratory_direction_fix extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."direction" DROP "enterprise_id";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."direction" ADD "enterprise_id" INT REFERENCES "mis"."enterprise_params"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}