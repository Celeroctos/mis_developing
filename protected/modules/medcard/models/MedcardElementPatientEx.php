<?php

class MedcardElementPatientEx extends MedcardElementForPatient {

    public function fetchWithGreeting($category) {
        $rows = $this->getDbConnection()->createCommand()
            ->select('count(*)')
            ->from('mis.medcard_elements_patient as p')
            ->where('p.categorie_id = :category_id', [
                ':category_id' => $category
            ])->andWhere('p.type != -1')
//            ->limit(100)
            ->queryAll();
        return $rows;
    }
}