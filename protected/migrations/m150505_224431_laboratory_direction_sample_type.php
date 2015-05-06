<?php

class m150505_224431_laboratory_direction_sample_type extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."direction" ADD "sample_type_id" INT REFERENCES "lis"."sample_type"("id") ON DELETE SET DEFAULT DEFAULT NULL
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$this->execute("ALTER TABLE lis.direction DROP sample_type_id");
	}
}