<?php

class m150321_094522_laboratory_analysis extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
			CREATE TABLE "lis"."analysis" (
			  "id" SERIAL PRIMARY KEY,
			  "registration_date" TIMESTAMP DEFAULT now(),
			  "direction_id" INT REFERENCES "lis"."direction"("id"),
			  "doctor_id" INT REFERENCES "mis"."doctors"("id"),
			  "medcard_number" VARCHAR(50) REFERENCES "mis"."medcards"("card_number")
			);
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			DROP TABLE "lis"."analysis";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}