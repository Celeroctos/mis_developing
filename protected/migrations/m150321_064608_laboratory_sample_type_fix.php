<?php

class m150321_064608_laboratory_sample_type_fix extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."sample_type" ALTER COLUMN "parent_id" SET DEFAULT NULL;
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."sample_type" ALTER COLUMN "parent_id" DROP DEFAULT;
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}