<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'analysis-type-template-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="modal-body">
        <div class="col-xs-12">
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'analysis_type_id', array(
                        'class' => 'col-xs-8 control-label'
                    ));
                    ?>
                    <div class="col-xs-4">
                        <?php
                        echo $form->dropDownList($model, 'analysis_type_id', AnalysisType::getAnalysisTypeListData('insert'), ['class' => 'form-control', /* 'disabled'=> true */]
                        );
                        ?>       
                        <?php echo $form->error($model, 'analysis_type_id'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'analysis_param_id', array(
                        'class' => 'col-xs-8 control-label'
                    ));
                    ?>
                    <div class="col-xs-4">
                        <?php
                        echo $form->dropDownList($model, 'analysis_param_id', AnalysisParam::getAnalysisParamListData('insert')
                        );
                        ?>       
                        <?php echo $form->error($model, 'analysis_param_id'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'is_default', array(
                        'class' => 'col-xs-8 control-label'
                    ));
                    ?>
                    <div class="col-xs-4">
                        <?php
                        echo $form->dropDownList($model, 'is_default', ['0' => 'Нет', '1' => 'Да']
                        );
                        ?>       
                        <?php echo $form->error($model, 'is_default'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'seq_number', array(
                        'class' => 'col-xs-8 control-label'
                    ));
                    ?>
                    <div class="col-xs-4">
                        <?php echo $form->textField($model, 'seq_number', array('class' => 'col-xs-8')); ?>
                        <?php echo $form->error($model, 'seq_number'); ?>
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
                    $("#DialogCRUDForm").html(r).dialog("option", "title", "' . ($model->isNewRecord ? 'Create' : 'Update') . ' AnalysisParam").dialog("open"); return false;
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
    </div>

</div><!-- form -->