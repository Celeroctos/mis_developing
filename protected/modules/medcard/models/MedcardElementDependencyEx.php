<?php

class MedcardElementDependencyEx extends MedcardElementPatientDependence {

    const ACTION_NONE = 0;
    const ACTION_HIDE = 1;
    const ACTION_SHOW = 2;

    public function getActionList() {
        return [
            static::ACTION_NONE => 'Нет',
            static::ACTION_HIDE => 'Спрятать',
            static::ACTION_SHOW => 'Показать',
        ];
    }
}