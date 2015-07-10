<?php

class Laboratory_Widget_AboutPatient extends Widget {

    public $medcard;
    public $patient;
    public $address;

    public function init() {
        if (is_scalar($this->patient) && !$this->patient = Laboratory_Patient::model()->findByPk($this->patient)) {
            throw new CException('Unresolved patient identification number');
        } else if (empty($this->patient)) {
            throw new CException('Patient can\'t be empty');
        }
        if (is_scalar($this->medcard) && !$this->medcard = Laboratory_Medcard::model()->findByPk($this->medcard)) {
            throw new CException('Unresolved medcard identification number');
        } else if (empty($this->medcard)) {
            throw new CException('Medcard can\'t be empty');
        }
        if (is_scalar($this->address) && !$this->address = Laboratory_Address::model()->findByPk($this->address)) {
            throw new CException('Unresolved address identification number');
        } else if (empty($this->address)) {
            throw new CException('Address can\'t be empty');
        }
    }

    public function run() {
        $age = DateTime::createFromFormat('Y-m-d', $this->patient->{'birthday'})
            ->diff(new DateTime())->y;
        return $this->render('Laboratory_Widget_AboutPatient', [
            'patient' => $this->patient,
            'medcard' => $this->medcard,
            'age' => $age,
            'address' => $this->address
        ]);
    }
}