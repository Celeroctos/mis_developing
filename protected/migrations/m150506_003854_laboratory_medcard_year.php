<?php

class m150506_003854_laboratory_medcard_year extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."medcard" ADD "year" SMALLINT DEFAULT EXTRACT(YEAR FROM now())
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."medcard" DROP "year"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}