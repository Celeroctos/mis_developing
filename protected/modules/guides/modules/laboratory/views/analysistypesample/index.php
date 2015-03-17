<script type="text/javascript">
    globalVariables.guideEdit = '<?php echo Yii::app()->user->checkAccess('guideEditAnalysisType'); ?>';</script>

<h4>Справочники лаборатории</h4>
<?php
$this->widget('application.modules.guides.components.widgets.LaboratoryTabMenu', array());
?>
<?php
$this->pageTitle = 'Образцы для типов анализов';
?>
<h4>Образцы для типов анализов</h4>

<?php if (Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-danger">
        <?= Yii::app()->user->getFlash('error'); ?>
        <?= Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>



<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<?php echo CHtml::label('Выберите тип анализа', 'analysis_type_id'); ?>
<?php echo $form->dropDownList($model, 'analysis_type_id', AnalysisType::getAnalysisTypeListData('insert')); ?>
<?php echo CHtml::submitButton('Выбрать', ['class' => 'btn btn-default']); ?>

<?php $this->endWidget(); ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'DialogCRUDForm',
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'height' => 'auto',
        'resizable' => 'false',
    ),
));
$this->endWidget();

$updateDialog = <<<'EOT'
function() {
	var url = $(this).attr('href');
    $.get(url, function(r){
        $("#update").html(r).dialog("open");
		$("#DialogCRUDForm").html(r).dialog("option", "title", "Радактирование образца в списке").dialog("open");
    });
    return false;
}
EOT;

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('analysis-type-sample-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
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
    ?>
    <?php if ($model->analysis_type_id > 0) { ?>
        <?=
        CHtml::link('Добавить', $this->createUrl('analysistypesample/add/id/' . $model->analysis_type_id), [ 'class' => 'btn btn-primary', 'ajax' => array(
                'url' => $this->createUrl('analysistypesample/add/id/' . $model->analysis_type_id),
                'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление образца к списку").dialog("open"); return false;}',
            ),
        ]);
        ?>
    <?php } ?>

<?php } ?>

<?php if ($model->analysis_type_id > 0) { ?>
    <?php
    $this->widget('zii.widgets.grid.CGridView', [
        'id' => 'analysis-type-sample-grid',
        'ajaxUpdate' => false,
        'ajaxType' => 'POST',
        'dataProvider' => $model->search(),
#	'filter'=>$model,
        'itemsCssClass' => 'table table-bordered',
        'columns' => [
            [
                'name' => 'id',
                'headerHtmlOptions' => array('style' => 'display:none'),
                'htmlOptions' => array('style' => 'display:none'),],
            [
                'name' => 'analysis_sample_id',
                'value' => '$data->analysisSamples->type',
            ],
            [
                'name' => 'Подтип образца',
                'value' => '$data->analysisSamples->subtype',
            ],
            [
                'class' => 'CButtonColumn',
                'deleteConfirmation' => "js:'Вы уверены, что хотите удалить образец \'' + $(this).parent().parent().children(':nth-child(2)').text() + '\' из списка?'",
                'template' => $template,
                'buttons' => $buttons,
            ],
        ],
            ]
    );
    ?>
<?php } ?>
