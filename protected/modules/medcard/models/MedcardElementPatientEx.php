<?php

class MedcardElementPatientEx extends MedcardElementForPatient {

    public function fetchWithGreeting($category) {
        $rows = $this->getDbConnection()->createCommand()
            ->select([
                'p.element_id as id',
                'p.label_before as label',
                'p.*'
            ])
            ->from('mis.medcard_elements_patient as p')
            ->where('p.categorie_id = :id', [
                ':id' => $category
            ])->andWhere('p.type <> -1')
            ->order('change_date')
            ->queryAll();
        $result = [];
        foreach ($rows as $k => $row) {
            $result[$row['id']] = $row;
        }
        return array_values($result);
    }
}