<script type="text/javascript">
    globalVariables.guideEdit = '<?php echo Yii::app()->user->checkAccess('guideEditAnalyzerType'); ?>';</script>

<h4>Справочники лаборатории</h4>
<?php
$this->widget('application.modules.guides.components.widgets.LaboratoryTabMenu', array());
?>
<?php
$this->pageTitle = 'Типы анализов для типов анализаторов';
?>
<h4>Типы анализов для типов анализаторов</h4>

<?php if (Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-danger">
        <?= Yii::app()->user->getFlash('error'); ?>
        <?= Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>



<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

        <?php echo CHtml::label('Выберите тип анализатора','analysis_type_id'); ?>
        <?php echo $form->dropDownList($model,'analyser_type_id', AnalyzerType::getAnalyzerTypeListData('insert')); ?>
        <?php echo CHtml::submitButton('Выбрать',['class' => 'btn btn-default']); ?>

<?php $this->endWidget(); ?>

<?php

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
		$("#DialogCRUDForm").html(r).dialog("option", "title", "Редактирование списка типа анализатора").dialog("open");
    });
    return false;
}
EOT;

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('analysis-type-param-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php 
$template = '';
if ( Yii::app()->user->checkAccess('guideEditAnalyzerType')) {
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
<?php if ($model->analyser_type_id > 0) { ?>
    <?=
    CHtml::link('Добавить', $this->createUrl('analyzertypeanalysis/add/id/'.$model->analyser_type_id), [ 'class' => 'btn btn-primary', 'ajax' => array(
        'url' => $this->createUrl('analyzertypeanalysis/add/id/'.$model->analyser_type_id),
        'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление типа аназиза к списку").dialog("open"); return false;}',
        ),
    ]);
    ?>
    <?php } ?>

    <?php } ?>

<?php if ($model->analyser_type_id > 0) { ?>
<?php 
$this->widget('zii.widgets.grid.CGridView', [
	'id'=>'analyzer-type-analysis-grid',
	'ajaxUpdate'=>false,
    'ajaxType'=>'POST',
	'dataProvider'=>$model->search(),
#	'filter'=>$model,
    'itemsCssClass' => 'table table-bordered',
	'columns'=>[
                [
            'name'=>'id',
            'headerHtmlOptions' => array('style' => 'display:none'),
            'htmlOptions' => array('style' => 'display:none'),        ],
#                [
#            'name'=>'analysis_type_id',
#            'headerHtmlOptions' => array('style' => 'display:none'),
#            'htmlOptions' => array('style' => 'display:none'),        ],
        [
            'name' => 'analyser_type_id',
            'value'=> '$data->analysisTypes->name',
        ],
		[
			'class'=>'CButtonColumn',
            'deleteConfirmation' => "js:'Вы уверены, что хотите удалить тип анализа \'' + $(this).parent().parent().children(':nth-child(2)').text() + '\' из списка?'",
            'template' => $template,
			'buttons' => $buttons,
		], 
        ],
]
); ?>
    <?php } ?>
