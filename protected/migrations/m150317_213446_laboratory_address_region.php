<?php

class m150317_213446_laboratory_address_region extends CDbMigration {

	public function safeUp() {
		$sql = <<< SQL
			ALTER TABLE "lis"."address" ADD region_name VARCHAR(100);
			ALTER TABLE "lis"."address" ADD district_name VARCHAR(100);
			ALTER TABLE "lis"."address" DROP "city";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			ALTER TABLE "lis"."address" DROP region_name;
			ALTER TABLE "lis"."address" DROP district_name;
			ALTER TABLE "lis"."address" ADD "city_name" VARCHAR(50);
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}