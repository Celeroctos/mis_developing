<?php

namespace laboratory\controllers;

use ControllerEx;

class PatientController extends ControllerEx {

    public function actionSave() {
        $attributes = [ 'id', 'surname', 'name', 'patient', 'sex', 'birthday', 'contact', 'work_place', ];
        $form = $this->requireModel('Laboratory_Form_Patient', $attributes);
        if (!$patient = \Laboratory_Patient::model()->findByPk($form['id'])) {
            throw new \CHttpException(404, 'Unresolved patient identification number ('. $form['id'] .')');
        }
        $patient->setAttributes($form->getAttributes($attributes), false);
        if (!$patient->save()) {
            throw new \CException('An error occurred while saving patient data');
        }
        $this->success('Изменения сохранены');
    }
}