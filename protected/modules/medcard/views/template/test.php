<?php
/**
 * @var $this TemplateController
 */

$this->widget('MedcardCategoryWidget', [
	'category' => MedcardCategoryEx::model()->findByAttributes([ 'id' => 528 ])
]);