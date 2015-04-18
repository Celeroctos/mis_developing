<?php

class m150418_014805_laboratory_patient_contact extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
		ALTER TABLE "lis"."patient" ADD "contact" VARCHAR(200)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."patient" DROP "contact";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}