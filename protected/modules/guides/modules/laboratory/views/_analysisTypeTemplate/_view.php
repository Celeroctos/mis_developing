<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('analysis_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->analysis_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('analysis_param_id')); ?>:</b>
	<?php echo CHtml::encode($data->analysis_param_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_default')); ?>:</b>
	<?php echo CHtml::encode($data->is_default); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seq_number')); ?>:</b>
	<?php echo CHtml::encode($data->seq_number); ?>
	<br />


</div>