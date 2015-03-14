<?php

class m150314_030520_laboratory_patient_policy_fixes extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."patient" DROP "is_policy_voluntary";
			ALTER TABLE "lis"."patient" ADD "document_type" INT REFERENCES "mis"."doctypes"("id")
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."patient" DROP "document_type";
			ALTER TABLE "lis"."patient" ADD "is_policy_voluntary" INTEGER DEFAULT 0;
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}