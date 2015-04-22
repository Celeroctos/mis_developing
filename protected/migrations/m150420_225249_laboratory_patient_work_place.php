<?php

class m150420_225249_laboratory_patient_work_place extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."patient" ADD "work_place" VARCHAR(200)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."patient" DROP "work_place";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}