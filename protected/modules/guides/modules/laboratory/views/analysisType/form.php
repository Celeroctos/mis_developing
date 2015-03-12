<!--<div class="modal-dialog">-->
    
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'analysis-type-form',
        'enableAjaxValidation' => false,
    ));
    ?>
<?php
$updateDialog = <<<'EOT'
function() {
    var url = $(this).attr('href');
    $.get(url, function(r){
        $("#update").html(r).dialog("open");
        $("#DialogCRUDForm").html(r).dialog("option", "title", "Редактирование типа анализа").dialog("open");
    });
    return false;
}
EOT;

?>

    <div class="modal-body">
        <div class="col-xs-12">
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'name', array(
                        'class' => 'col-xs-3 control-label'
                    ));
                    ?>
                    <div class="col-xs-9">
                        <?php
                        echo $form->textField($model, 'name', array(
                            'id' => 'name',
                            'class' => 'form-control',
                            'placeholder' => 'Наименование типа анализа'
                        ));
                        ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'short_name', array(
                        'class' => 'col-xs-3 control-label'
                    ));
                    ?>
                    <div class="col-xs-9">
                        <?php
                        echo $form->textField($model, 'short_name', array(
                            'id' => 'short_name',
                            'class' => 'form-control',
                            'placeholder' => 'Краткое наименование типа анализа'
                        ));
                        ?>
                        <?php echo $form->error($model, 'short_name'); ?>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'metodics'); ?>
                    <?php
                    echo $form->radioButtonList($model, 'metodics', array('Не определена', 'Автоматическая', 'Ручная'), array(
                        'id' => 'metodics',
#                            'class' => 'form-control',
                        'separator' => '&nbsp',
                    ));
                    ?>
                    <?php echo $form->error($model, 'metodics'); ?>
                </div>
            </div>
        </div> 
<?php
            if ($analysistypetemplate) {
        ?>        
<?=
CHtml::link('Добавить', $this->createUrl('analysistypetemplate/create/id/'.$model->id), 
[
 'class' => 'btn btn-primary', 
 'id' => 'analysis_type_template_add',

'ajax' => array(
        'url' => $this->createUrl('analysistypetemplate/create/id/'.$model->id),
        'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление параметра к списку параметров типа анализа").dialog("open"); return false;}',
    ),
]);
?> 
<?php
            }          
                    ?>        
<?php 
    $template = '';
    if (Yii::app()->user->checkAccess('guideEditAnalysisType')) { 
    $template = '{update} {delete}';
    $buttons = array(
                'headerHtmlOptions' => array(
                    'class' => 'col-md-1',
                ),
                'update' => array(
                    'click' => $updateDialog,
                    'imageUrl' => false,
                    'options' => [
                        'class' => 'btn btn-primary btn-block btn-xs',
                    ],
                ),
                'delete' => array(
                    'imageUrl' => false,
                    'options' => [
                        'class' => 'btn btn-default btn-block btn-xs',
                    ],
                )
        );
    };

    ?>
        
<?php 
if ($analysistypetemplate) {
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'analysis-type-template-grid',
    'ajaxUpdate'=>false,
    'dataProvider'=>$analysistypetemplate,
//    'filter'=>$model,
    'columns'=>array(
        'id',
        'analysis_type_id',
        'analysis_param_id',
        'is_default',
        array(
            'class'=>'CButtonColumn',
            'template' => $template,
            'buttons' => $buttons,
            ), 
        ),
    )
); 
}
?>
        
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
					$("#DialogCRUDForm").html(r).dialog("option", "title", "' . ($model->isNewRecord ? 'Create' : 'Update') . ' AnalysisType").dialog("open"); return false;
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

    </div>
        <?php $this->endWidget(); ?>

</div><!-- form -->