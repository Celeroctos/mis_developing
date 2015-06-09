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
		<?php $this->widget('MedcardElementWidget', [ 'element' => $e ]) ?>
	</div>
	<hr>
<?php endforeach ?>
