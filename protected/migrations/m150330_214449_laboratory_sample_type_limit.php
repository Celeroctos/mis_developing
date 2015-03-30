<?php

class m150330_214449_laboratory_sample_type_limit extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."sample_type" DROP "name" CASCADE;
		ALTER TABLE "lis"."sample_type" ADD "name" VARCHAR(100)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."sample_type" DROP "name" CASCADE;
		ALTER TABLE "lis"."sample_type" ADD "name" VARCHAR(20)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}