<?php

class m150709_202822_laboratory_analyzer_limit extends CDbMigration
{
	public function safeUp()
	{		$sql = <<< SQL
ALTER TABLE "lis"."analyzer" ADD "limit" INT
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown()
	{
		$sql = <<< SQL
ALTER TABLE "lis"."analyzer" DROP "limit"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}