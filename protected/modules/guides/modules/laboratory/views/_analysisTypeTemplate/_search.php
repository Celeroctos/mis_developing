<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'analysis_type_id'); ?>
		<?php echo $form->textField($model,'analysis_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'analysis_param_id'); ?>
		<?php echo $form->textField($model,'analysis_param_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_default'); ?>
		<?php echo $form->textField($model,'is_default'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'seq_number'); ?>
		<?php echo $form->textField($model,'seq_number'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->