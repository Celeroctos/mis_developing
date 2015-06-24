<?php

class m150624_052108_medcard_entry extends CDbMigration
{
	public function safeUp() {
        $sql = <<< SQL

CREATE SCHEMA "medcard";

CREATE TABLE "medcard"."medcard_guide" (
  "id" SERIAL PRIMARY KEY,
  "name" TEXT NOT NULL
);

CREATE TABLE "medcard"."medcard_guide_value" (
  "id" SERIAL PRIMARY KEY,
  "guide_id" INT REFERENCES "medcard"."medcard_guide"("id") ON DELETE CASCADE,
  "value" TEXT NOT NULL,
  "greeting_id" INT DEFAULT NULL
);

CREATE TABLE "medcard"."medcard_template" (
  "id" SERIAL PRIMARY KEY,
  "name" VARCHAR(150) NOT NULL,
  "page_id" INT,
  "primary_diagnosis" INT DEFAULT 0,
  "index" INT
);

CREATE TABLE "medcard"."medcard_element" (
  "id" SERIAL PRIMARY KEY,
  "parent_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE,
  "type" INT,
  "category_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE,
  "label_before" VARCHAR(200),
  "label_after" VARCHAR(200),
  "label_display" VARCHAR(200),
  "guide_id" INT DEFAULT 0,
  "default_value" TEXT DEFAULT NULL,
  "size" INT,
  "flags" INT DEFAULT 0,
  "position" INT,
  "config" TEXT DEFAULT NULL
);

CREATE TABLE "medcard"."medcard_template_to_element" (
  "template_id" INT REFERENCES "medcard"."medcard_template"("id") ON DELETE CASCADE,
  "category_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE
);

CREATE TABLE "medcard"."medcard_greeting_element" (
  "id" SERIAL PRIMARY KEY,
  "element_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE,
  "parent_id" INT REFERENCES "medcard"."medcard_greeting_element"("id") ON DELETE CASCADE,
  "value_id" INT REFERENCES "medcard"."medcard_guide_value"("id") ON DELETE CASCADE,
  "change_date" TIMESTAMP DEFAULT now(),
  "greeting_id" INT DEFAULT NULL,
  "flags" INT DEFAULT 0
);

CREATE TABLE "medcard"."medcard_dependency" (
  "id" SERIAL PRIMARY KEY,
  "element_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE,
  "value" INT REFERENCES "medcard"."medcard_guide_value"("id") ON DELETE CASCADE,
  "dependent_id" INT REFERENCES "medcard"."medcard_element"("id") ON DELETE CASCADE,
  "action" INT
);

CREATE TABLE "medcard"."medcard_comment" (
  "id" SERIAL PRIMARY KEY,
  "comment" TEXT,
  "medcard_id" VARCHAR(50) REFERENCES "mis"."medcards"("card_number") ON DELETE CASCADE,
  "creation_date" TIMESTAMP DEFAULT now(),
  "doctor_id" INT REFERENCES "mis"."doctors"("id") ON DELETE CASCADE
)

SQL;
        foreach (explode(";", $sql) as $s) {
            $this->execute($s);
        }
	}

	public function safeDown() {
        $sql = <<< SQL

DROP TABLE "medcard"."medcard_comment";
DROP TABLE "medcard"."medcard_dependency";
DROP TABLE "medcard"."medcard_greeting_element";
DROP TABLE "medcard"."medcard_template_to_element";
DROP TABLE "medcard"."medcard_element";
DROP TABLE "medcard"."medcard_template";
DROP TABLE "medcard"."medcard_guide_value";
DROP TABLE "medcard"."medcard_guide";

DROP SCHEMA "medcard";

SQL;
        foreach (explode(";", $sql) as $s) {
            $this->execute($s);
        }
	}
}