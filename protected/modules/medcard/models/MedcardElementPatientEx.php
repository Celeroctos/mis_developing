<?php

class MedcardElementPatientEx extends MedcardElementForPatient {

    public function fetchWithGreeting($category) {
        $elements = MedcardElementEx::model()->fetchWithGreeting($category);
        $fix = [];
        foreach ($elements as $e) {
            $fix[] = $e['id'];
        }
        if (!empty($fix)) {
            $clause = 'AND "element_id" IN ('. implode(',', $fix) .')';
        } else {
            $clause = '';
        }
        $sql = <<< TEXT
WITH "patient" AS (
    SELECT * FROM "mis"."medcard_elements_patient" WHERE
        "categorie_id" = :category_id AND "type" <> -1 $clause
    ORDER BY change_date DESC LIMIT 1
) SELECT "e".*, "p"."value" as "value" FROM "mis"."medcard_elements" AS "e", "patient" AS "p" WHERE
    "e"."categorie_id" = :category_id AND "e"."type" <> -1
TEXT;
        return $this->getDbConnection()->createCommand($sql)
            ->bindParam(':category_id', $category)->queryAll();
    }
}