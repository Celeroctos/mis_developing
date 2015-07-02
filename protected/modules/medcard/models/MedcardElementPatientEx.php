<?php

class MedcardElementPatientEx extends MedcardElementForPatient {

    public function fetchWithGreeting($category) {
        $elements = MedcardElementEx::model()->fetchWithGreeting($category);
        return $elements;
    }

    public function fetchByCategory($category, $greeting) {
        /* $history = $this->getDbConnection()->createCommand()
            ->select('history_id')
            ->from('mis.medcard_elements_patient')
            ->where('greeting_id = :greeting_id and categorie_id = :category_id', [
                ':greeting_id' => $greeting,
                ':category_id' => $category,
            ])
            ->order('history_id desc')
            ->limit(1)
            ->queryRow();
        if ($history != null) {
            $history = $history['history_id'];
        } else {
            $history = 1;
        }
        $rows = $this->getDbConnection()->createCommand()
            ->select('*')
            ->from('mis.medcard_elements_patient')
            ->where('greeting_id = :greeting_id and categorie_id = :category_id and history_id = :history_id', [
                ':greeting_id' => $greeting, ':category_id' => $category, ':history_id' => $history
            ])->queryAll();
        return $rows; */
        $identifiers = [];
        $rows = $this->getDbConnection()->createCommand()
            ->select('max(history_id) as history_id, max(change_date) as change_date')
            ->from('mis.medcard_elements_patient')
            ->where('categorie_id = :category_id and value is not null and greeting_id = :greeting_id', [
                ':category_id' => $category,
                ':greeting_id' => $greeting
            ])->group('element_id')
            ->order('change_date desc')
            ->queryAll();
        foreach ($rows as $row) {
            $identifiers[] = '\''.$row['change_date'].'\'';
        }
        if (empty($identifiers)) {
            return [];
        }
        $rows = $this->getDbConnection()->createCommand()
            ->select('*')
            ->from('mis.medcard_elements_patient')
            ->where('change_date in ('. implode(',', $identifiers) .')')
            ->queryAll();
        return $rows;
        /* $rows = $this->getDbConnection()->createCommand()
            ->select('*, element_id as id')
            ->from('mis.medcard_elements_patient')
            ->where('categorie_id = :category_id', [
                ':category_id' => $category
            ])->order('change_date desc')
            ->queryAll();
        return $rows; */
    }

    public function fetchByTemplate($template, $greeting) {
        $categories = MedcardTemplateEx::model()->fetchCategories($template, false);
        $result = [];
        foreach ($categories as $c) {
            $result = array_merge($result, $this->fetchByCategory($c, $greeting));
        }
        return $result;
//
//        $sql = <<< SQL
//WITH "patient" AS (
//  SELECT * FROM "mis"."medcard_elements_patient"
//    WHERE "categorie_id" = :category_id
//      AND "type" <> -1
//    ORDER BY "change_date" DESC
//  LIMIT 1
//) SELECT * FROM "mis"."medcard_elements" AS "e"
//  LEFT OUTER JOIN "patient" AS "p"
//    ON "p"."element_id" = "e"."id";
//SQL;
//        $this->getDbConnection()->createCommand($sql)->queryAll();
//
//        $rows = $this->getDbConnection()->createCommand()
//            ->select('*, element_id as id')
//            ->from('mis.medcard_elements_patient as p')
//            ->join('mis.medcard_categories as c', 'p.categorie_id = c.id')
//            ->where('c.id in ('. implode(',', $categories) .')')
//            ->order('p.change_date desc')
//            ->queryAll();
//        return $rows;
    }
}