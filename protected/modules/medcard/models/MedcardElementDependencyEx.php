<?php

class MedcardElementDependencyEx extends MedcardElementDependence {

    const ACTION_NONE = 0;
    const ACTION_HIDE = 1;
    const ACTION_SHOW = 2;

    public function fetchArray($condition = '', $params = []) {
        $query = $this->getDbConnection()->createCommand()
            ->select('d.*, e.path as dep_path')
            ->from('mis.medcard_elements_dependences d')
            ->join('mis.medcard_elements as e', 'd.dep_element_id = e.id')
            ->where('action <> 0');
        if (!empty($condition)) {
            $query->where($condition, $params);
        }
        return $query->queryAll();
    }

    public function getActionList() {
        return [
            static::ACTION_NONE => 'Нет',
            static::ACTION_HIDE => 'Спрятать',
            static::ACTION_SHOW => 'Показать',
        ];
    }
}