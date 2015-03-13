<?php

/*$this->menu=array(
	array('label'=>'List AnalysisTypeTemplate', 'url'=>array('index')),
	array('label'=>'Create AnalysisTypeTemplate', 'url'=>array('create'), 'linkOptions'=>array(
		'ajax' => array(
			'url'=>$this->createUrl('create'),
			'success'=>'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Create AnalysisTypeTemplate").dialog("open"); return false;}',
		),
	)),
);
*/
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
        'id'=>'DialogCRUDForm',
        'options'=>array(
			'autoOpen'=>false,
			'modal'=>true,
			'width'=>'auto',
			'height'=>'auto',
			'resizable'=>'false',
		),
	));
$this->endWidget();

$updateDialog =<<<'EOT'
function() {
	var url = $(this).attr('href');
    $.get(url, function(r){
        $("#update").html(r).dialog("open");
		$("#DialogCRUDForm").html(r).dialog("option", "title", "Update AnalysisTypeTemplate").dialog("open");
    });
    return false;
}
EOT;


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('analysis-type-template-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Analysis Type Templates</h1>

<?php
    if (Yii::app()->user->checkAccess('guideEditAnalysisType')) { 
?>
<?=
CHtml::link('Добавить', $this->createUrl('#'), [ 'class' => 'btn btn-primary', 'ajax' => array(
            'url'=>$this->createUrl('create'),
            'success'=>'js:function(r){
            $("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление параметра в список для типа анализа").dialog("open"); 
            return false;
            }',
    ),
]);
?>
<?php
    } 
?>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

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

    ?>
<?php } ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'analysis-type-template-grid',
	'ajaxUpdate'=>false,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'analysis_type_id',
		'analysis_param_id',
		'is_default',
		'seq_number',
        array(
            'class' => 'CButtonColumn',
            'deleteConfirmation' => "js:'Вы уверены, что хотите удалить параметр из шаблона типа анализа \'' + $(this).parent().parent().children(':nth-child(1)').text() + '\'?'",
            'template' => $template,
            'buttons' => $buttons,
        ),
	),
)); ?>
