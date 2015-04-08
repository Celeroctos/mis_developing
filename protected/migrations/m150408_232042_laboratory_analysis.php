<?php

class m150408_232042_laboratory_analysis extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL

		DROP TABLE "lis"."analysis";

		CREATE TABLE "lis"."analyzer" (
			"id" SERIAL PRIMARY KEY,
			"analyzer_type_id" INT REFERENCES "lis"."analyzer_type"("id"),
			"name" VARCHAR(100) DEFAULT NULL,
			"serial_number" VARCHAR(50) DEFAULT NULL,
			"model" VARCHAR(50) DEFAULT NULL,
			"software_version" VARCHAR(10) DEFAULT NULL
		);

		CREATE TABLE "lis"."analysis" (
		  "id" SERIAL PRIMARY KEY,
		  "registration_time" TIMESTAMP DEFAULT now(),
		  "direction_id" INT REFERENCES "lis"."direction"("id"),
		  "analyzer_id" INT REFERENCES "lis"."analyzer"("id")
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL

		DROP TABLE "lis"."analysis";
		DROP TABLE "lis"."analyzer";

		CREATE TABLE "lis"."analysis" (
		  "id" SERIAL PRIMARY KEY,
		  "registration_date" TIMESTAMP DEFAULT now(),
		  "direction_id" INT REFERENCES "lis"."direction"("id"),
		  "doctor_id" INT REFERENCES "mis"."doctors"("id"),
		  "medcard_number" VARCHAR(50) REFERENCES "mis"."medcards"("card_number")
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}