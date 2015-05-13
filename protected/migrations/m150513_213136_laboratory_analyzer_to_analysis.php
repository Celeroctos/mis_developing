<?php

class m150513_213136_laboratory_analyzer_to_analysis extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<< SQL

		CREATE TABLE "lis"."analyzer_type_to_analysis_type" (
		  "analyzer_type_id" INT REFERENCES "lis"."analyzer_type"("id") ON DELETE CASCADE,
		  "analysis_type_id" INT REFERENCES "lis"."analysis_type"("id") ON DELETE CASCADE
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown()
	{
		$sql = <<< SQL
		DROP TABLE "lis"."analyzer_type_to_analysis_type"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}