<?php
/**
 * @var $this TemplateController
 */

$category = MedcardCategoryEx::model()->findByAttributes([
	'id' => 528
]);

$elements = MedcardElementEx::model()->findAllByAttributes([
	'categorie_id' => $category->getAttribute('id')
]) ?>

<?php foreach ($elements as $e): ?>
	<div class="row">
		<?= $e->getAttribute('label') ?>
		<?php $this->widget('MedcardElementWidget', [ 'element' => $e ]) ?>
	</div>
	<br>
<?php endforeach ?>
