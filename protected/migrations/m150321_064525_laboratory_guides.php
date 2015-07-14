<?php

class m150321_064525_laboratory_guides extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL

		CREATE SCHEMA "lis";

		CREATE TABLE "lis"."analysis_type" (
		  "id" SERIAL PRIMARY KEY,
		  "short_name" VARCHAR(20) NOT NULL,
		  "name" VARCHAR(255) NOT NULL,
		  "technique" INT
		);

		CREATE TABLE "lis"."analysis_type_parameter" (
		  "id" SERIAL PRIMARY KEY,
		  "short_name" VARCHAR(20) NOT NULL,
		  "name" VARCHAR(255) NOT NULL,
		  "is_default" INT NOT NULL DEFAULT 1
		);

		CREATE TABLE "lis"."analysis_type_to_analysis_type_parameter" (
		  "id" SERIAL PRIMARY KEY,
		  "analysis_type_parameter" INT REFERENCES "lis"."analysis_type_parameter"("id")  ON DELETE CASCADE,
		  "analysis_type" INT REFERENCES "lis"."analysis_type"("id") ON DELETE CASCADE
		);

		CREATE TABLE "lis"."sample_type" (
		  "id" SERIAL PRIMARY KEY,
		  "name" VARCHAR(20),
		  "parent_id" INT REFERENCES "lis"."sample_type"("id") DEFAULT NULL
		);

		CREATE TABLE "lis"."enterprise_clef" (
		  "enterprise_id" INT REFERENCES "mis"."enterprise_params"("id")
		);

		CREATE OR REPLACE VIEW "lis"."enterprise" AS
		  SELECT e.* FROM "mis"."enterprise_params" AS e JOIN "lis"."enterprise_clef" AS ec ON ec."enterprise_id" = e."id";

		CREATE TABLE "lis"."doctor_clef" (
		  "doctor_id" INT REFERENCES "mis"."doctors"("id")
		);

		CREATE OR REPLACE VIEW "lis"."doctor" AS
		  SELECT d.* FROM "mis"."doctors" AS d JOIN "lis"."doctor_clef" AS dc ON dc."doctor_id" = d."id";

		CREATE TABLE "lis"."analyzer_type" (
			"id" SERIAL PRIMARY KEY,
			"type_name" VARCHAR(50) NOT NULL,
			"name" VARCHAR(50) NOT NULL,
			"notes" TEXT DEFAULT ''
		);
		
		CREATE TABLE "lis"."analysis_type_to_sample_type" (
		  "id" SERIAL PRIMARY KEY,
		  "analysis_type_id" INT REFERENCES "lis"."analysis_type"("id") ON DELETE CASCADE,
		  "sample_type_id" INT REFERENCES "lis"."sample_type"("id") ON DELETE CASCADE
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL

		DROP TABLE "lis"."analysis_type_to_sample_type";
		DROP TABLE "lis"."analyzer_type";
		DROP VIEW "lis"."doctor";
		DROP TABLE "lis"."doctor_clef";
		DROP VIEW "lis"."enterprise";
		DROP TABLE "lis"."enterprise_clef";
		DROP TABLE "lis"."sample_type";
		DROP TABLE "lis"."analysis_type_to_analysis_type_parameter";
		DROP TABLE "lis"."analysis_type_parameter";
		DROP TABLE "lis"."analysis_type";

		DROP SCHEMA "lis";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}