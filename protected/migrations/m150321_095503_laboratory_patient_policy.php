<?php

class m150321_095503_laboratory_patient_policy extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."patient" ADD "policy_id" INT REFERENCES "lis"."policy"("id") DEFAULT NULL
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."patient" DROP "policy_id"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}