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
    }

    public function fetchGreetingCategories($greetingId, $categoryId) {
        return $this->getDbConnection()->createCommand()
            ->select('*, element_id as id, real_categorie_id as copy_id')
            ->from('mis.medcard_elements_patient')
            ->where('element_id = -1 and greeting_id = :greeting_id and categorie_id = :category_id', [
                ':greeting_id' => $greetingId,
                ':category_id' => $categoryId,
            ])->queryAll();
    }

    public function fetchHistoryByElement($elementId, $greetingId, $path = null) {
        $length = strlen($path);
        $query = $this->getDbConnection()->createCommand()
            ->select('*')
            ->from('mis.medcard_elements_patient')
            ->where('element_id = :element_id and greeting_id = :greeting_id', [
                ':element_id' => $elementId,
                ':greeting_id' => $greetingId,
            ])->order('history_id')
            ->limit(1);
        if (!empty($path)) {
            $query->andWhere('substring(path from 1 for '. $length .') = :path', [
                ':path' => $path
            ]);
        }
        return $query->queryRow();
    }

    public function fetchHistoryElementsByCategory($categoryId, $greetingId) {
        $sql = <<< 'SQL'
WITH "h" AS (
    SELECT "history_id" FROM
        "mis"."medcard_elements_patient"
    WHERE
        "categorie_id" = :category_id AND
        "greeting_id" = :greeting_id AND
        "element_id" <> -1
    ORDER BY "history_id" DESC
    LIMIT 1
) SELECT "p".*, "p"."element_id" AS "id", "p"."label_before" AS label FROM
    "mis"."medcard_elements_patient" AS "p", "h"
WHERE
    "p"."categorie_id" = :category_id AND
    "p"."greeting_id" = :greeting_id AND
    "p"."history_id" = h.history_id AND
    "element_id" <> -1
SQL;
        return $this->getDbConnection()->createCommand($sql)
            ->bindParam(':category_id', $categoryId)
            ->bindParam(':greeting_id', $greetingId)
            ->queryAll();
        /* return $this->getDbConnection()->createCommand()
            ->select('*')
            ->from('mis.medcard_elements_patient')
            ->where('element_id = :element_id and greeting_id = :greeting_id', [
                ':element_id' => $elementId,
                ':greeting_id' => $greetingId,
            ])->order('history_id')
            ->limit(1)
            ->queryRow(); */
    }

    public function fetchHistoryByCategory($categoryId, $greetingId) {
        $sql = <<< 'SQL'
WITH h AS (
    SELECT history_id FROM
        mis.medcard_elements_patient
    WHERE
        real_categorie_id = :category_id AND
        greeting_id = :greeting_id AND
        element_id = -1
    ORDER BY history_id DESC
    LIMIT 1
) SELECT p.*, p.real_categorie_id as id FROM
    mis.medcard_elements_patient AS p, h
WHERE
    p.categorie_id = :category_id AND
    p.greeting_id = :greeting_id AND
    p.history_id = h.history_id AND
    element_id = -1
SQL;
        return $this->getDbConnection()->createCommand($sql)
            ->bindParam(':category_id', $categoryId)
            ->bindParam(':greeting_id', $greetingId)
            ->queryAll();
        /* return $this->getDbConnection()->createCommand()
            ->select('*')
            ->from('mis.medcard_elements_patient')
            ->where('real_categorie_id = :category_id and greeting_id = :greeting_id and type = -1', [
                ':category_id' => $categoryId,
                ':greeting_id' => $greetingId,
            ])->order('history_id')
            ->limit(1)
            ->queryRow(); */
    }
}