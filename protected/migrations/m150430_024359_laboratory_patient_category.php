<?php

class m150430_024359_laboratory_patient_category extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
		CREATE TABLE "lis"."patient_category" (
			"id" SERIAL PRIMARY KEY,
			"pregnant" INT,
			"smokes" INT,
			"gestational_age" INT,
			"menstruation_cycle" INT,
			"race" INT
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		DROP TABLE "lis"."patient_category"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}