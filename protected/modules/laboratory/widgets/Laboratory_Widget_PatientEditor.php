<?php

class Laboratory_Widget_PatientEditor extends Widget {

    public $patient;

    public function init() {
        if (is_scalar($this->patient) && !$this->patient = Laboratory_Patient::model()->findByPk($this->patient)) {
            throw new CException('Unresolved patient identification number');
        } else if (empty($this->patient)) {
            throw new CException('Patient can\'t be empty');
        }
    }

    public function run() {
        return $this->render('Laboratory_Widget_PatientEditor', [
            'patient' => $this->patient,
            'model' => new Laboratory_Form_Patient('edit', $this->patient),
        ]);
    }
}