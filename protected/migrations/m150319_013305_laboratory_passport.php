<?php

class m150319_013305_laboratory_passport extends CDbMigration {

    public function safeUp() {
        $sql = <<< SQL
            CREATE TABLE "lis"."passport" (
              "id" SERIAL PRIMARY KEY,
              "surname" VARCHAR(100),
              "name" VARCHAR(100),
              "patronymic" VARCHAR(100),
              "series" VARCHAR(10),
              "number" VARCHAR(10),
              "subdivision_name" VARCHAR(200),
              "subdivision_code" VARCHAR(10),
              "issue_date" DATE
            );
            ALTER TABLE "lis"."patient" ADD "passport_id" INT REFERENCES "lis"."passport"("id") DEFAULT NULL;
SQL;
        foreach (explode(";", $sql) as $s) {
            $this->execute($s);
        }
    }

    public function safeDown() {
        $sql = <<< SQL
          ALTER TABLE "lis"."patient" DROP "passport_id";
          DROP TABLE "lis"."passport";
SQL;
        foreach (explode(";", $sql) as $s) {
            $this->execute($s);
        }
    }
}