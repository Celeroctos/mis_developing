<?php
/**
 * @var $this TemplateController
 */

$this->widget('MedcardCategoryWidget', [
    'category' => Yii::app()->getDb()->createCommand()
        ->select('*')
        ->from('medcard.medcard_element')
        ->where('id = :id', [
            ':id' => 49
        ])->queryRow()
]);