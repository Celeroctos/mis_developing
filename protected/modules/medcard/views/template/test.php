<?php
/**
 * @var $this TemplateController
 */

$this->widget('MedcardTemplateWidget', [
    'template' => MedcardTemplateEx::model()->findByAttributes([
        'id' => 40
    ])
]);