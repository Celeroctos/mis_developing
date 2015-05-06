<?php

class m150505_213902_laboratory_fixes extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL

		DELETE FROM "lis"."analysis";
		DELETE FROM "lis"."direction";

		ALTER TABLE "lis"."direction" ADD "patient_category_id" INT REFERENCES "lis"."patient_category"("id") ON DELETE CASCADE;

		CREATE TABLE "lis"."direction_parameter" (
		  "direction_id" INT REFERENCES "lis"."direction"("id") ON DELETE CASCADE,
		  "analysis_type_parameter_id" INT REFERENCES "lis"."analysis_type_parameter" ON DELETE CASCADE
		);

		ALTER TABLE "lis"."analysis_type_parameter" RENAME COLUMN "is_default" TO "checked";

		CREATE OR REPLACE VIEW "lis"."analysis_parameters" AS
		  SELECT "atp".*, "at_atp"."analysis_type" AS "analysis_type_id"
		  FROM "lis"."analysis_type_parameter" AS "atp"
		  INNER JOIN "lis"."analysis_type_to_analysis_type_parameter" AS "at_atp"
			ON "atp"."id" = "at_atp"."analysis_type_parameter"
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
		DROP VIEW "lis"."analysis_parameters";
		ALTER TABLE "lis"."analysis_type_parameter" RENAME COLUMN "checked" TO "is_default";
		DROP TABLE "lis"."direction_parameter";
		ALTER TABLE "lis"."direction" DROP "patient_category_id";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}