<?php
/**
 * @var $this Laboratory_Widget_PatientEditor
 * @var $patient Laboratory_Patient
 * @var $model Laboratory_Form_Patient
 */
$this->widget('AutoForm', [
    'url' => Yii::app()->createUrl('laboratory/patient/save'),
    'model' => $model,
]);