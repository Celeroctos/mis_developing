<?php

class m150709_202221_laboratory_document_type extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<< SQL
ALTER TABLE "lis"."passport" ADD "type" INT;
ALTER TABLE "lis"."passport" RENAME TO "document"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown()
	{
		$sql = <<< SQL
ALTER TABLE "lis"."document" RENAME TO "passport";
ALTER TABLE "lis"."passport" DROP "type"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}