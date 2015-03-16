<div class="modal-dialog">

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'machine-form',
	'enableAjaxValidation'=>false,
)); 
?>

    <div class="modal-body">
        <div class="col-xs-12">
            <div class="row"> 
                <div class="form-group">
		<?php echo $form->labelEx($model,'name', array(
                        'class' => 'col-xs-3 control-label'
                    )); ?>
                    <div class="col-xs-9">
		<?php echo $form->textField($model,'name', array(
                            'id' => 'name',
                            'class' => 'form-control',
                            'placeholder' => 'Название аналтзатора'
                        )); ?>
		<?php echo $form->error($model,'name'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
		<?php echo $form->labelEx($model,'serial', array(
                        'class' => 'col-xs-3 control-label'
                    )); ?>
                    <div class="col-xs-9">
		<?php echo $form->textField($model,'serial', array(
                            'id' => 'serial',
                            'class' => 'form-control',
                            'placeholder' => 'Номер'
                        )); ?>
		<?php echo $form->error($model,'serial'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
		<?php echo $form->labelEx($model,'model', array(
                        'class' => 'col-xs-3 control-label'
                    )); ?>
                    <div class="col-xs-9">
		<?php echo $form->textField($model,'model', array(
                            'id' => 'model',
                            'class' => 'form-control',
                            'placeholder' => 'Модель анализатора'
                        )); ?>
		<?php echo $form->error($model,'model'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
		<?php echo $form->labelEx($model,'software_version', array(
                        'class' => 'col-xs-3 control-label'
                    )); ?>
                    <div class="col-xs-9">
		<?php echo $form->textField($model,'software_version', array(
                            'id' => 'software_version',
                            'class' => 'form-control',
                            'placeholder' => 'Версия ПО'
                        )); ?>
		<?php echo $form->error($model,'software_version'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
        <div class="form-group">
            <?= $form->Label($model, 'analyzer_type_id', ['class'=>'col-xs-3 control-label']); ?>
            <div class="col-xs-4">
                <?= CHtml::activeDropDownList($model, 'analyzer_type_id', $analyzertypeList, [
                    'class'=>'form-control',
                ]); ?>
            </div>
        </div>
            </div> 
        </div> 
    </div> 

    <div class="modal-footer">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton', array(
            'name' => 'submit_' . rand(),
            'caption' => $model->isNewRecord ? 'Создать' : 'Сохранить',
            'htmlOptions' => array(
                'class' => 'btn btn-primary',
                'ajax' => array(
                    'url' => $model->isNewRecord ? $this->createUrl('create') : $this->createUrl('update', array('id' => $model->id)),
                    'type' => 'post',
                    'data' => 'js:jQuery(this).parents("form").serialize()',
                    'success' => 'function(r){
                                    if(r=="success"){
                    window.location.reload();
                                    }
                                    else{
                    $("#DialogCRUDForm").html(r).dialog("option", "title", "' . ($model->isNewRecord ? 'Create' : 'Update') . ' AnalyzerType").dialog("open"); return false;
                                    }
                }',
                ),
            ),
        ));

        $this->widget('zii.widgets.jui.CJuiButton', array(
            'buttonType' => 'button',
            'name' => 'close_' . rand(),
            'caption' => 'Закрыть',
            'htmlOptions' => array('class' => 'btn btn-default',
                'ajax' => array(
                    'url' => '#',
                    'type' => 'post',
                    'success' => 'function(r){
                                    window.location.reload();
                }',
                ),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>

    </div><!-- form -->