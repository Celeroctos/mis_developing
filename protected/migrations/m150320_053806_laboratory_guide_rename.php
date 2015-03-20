<?php

class m150320_053806_laboratory_guide_rename extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."analysis_types" RENAME COLUMN "metodics" TO "technique"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."analysis_types" RENAME COLUMN "technique" TO "metodics"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}