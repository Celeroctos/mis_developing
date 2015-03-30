<?php

class m150330_214449_laboratory_sample_type_limit extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL

		ALTER TABLE "lis"."sample_type" DROP "name" CASCADE;
		ALTER TABLE "lis"."sample_type" ADD "name" VARCHAR(100);

		CREATE VIEW "lis"."sample_type_tree" AS
		  WITH RECURSIVE tmp ("id", "parent_id", "name", PATH, LEVEL) AS (
			SELECT "t1"."id", "t1"."parent_id", "t1"."name", cast("t1"."name" AS TEXT) AS PATH, 1
			  FROM "lis"."sample_type" AS t1 WHERE "t1"."parent_id" IS NULL
			UNION SELECT "t2"."id", "t2"."parent_id", "t2"."name", cast(tmp.PATH || '->' || "t2"."name" AS TEXT), LEVEL + 1
			  FROM "lis"."sample_type" AS t2 INNER JOIN tmp ON tmp."id" = "t2".parent_id)
		  SELECT * FROM tmp ORDER BY tmp."path"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		ALTER TABLE "lis"."sample_type" DROP "name" CASCADE;
		ALTER TABLE "lis"."sample_type" ADD "name" VARCHAR(20)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}