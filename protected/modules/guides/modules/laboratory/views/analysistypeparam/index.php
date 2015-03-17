<script type="text/javascript">
    globalVariables.guideEdit = '<?php echo Yii::app()->user->checkAccess('guideEditAnalyzerType'); ?>';</script>

<h4>Справочники лаборатории</h4>
<?php
$this->widget('application.modules.guides.components.widgets.LaboratoryTabMenu', array());
?>
<?php
$this->pageTitle = 'Параметры для типов анализов';
?>
<h4>Параметры для типов анализов</h4>

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
<?php echo $form->dropDownList($model, 'analysis_type_id', AnalysisType::getAnalysisTypeListData('insert'), ['id' => 'analysis_type_dropdown']); ?>
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
		$("#DialogCRUDForm").html(r).dialog("option", "title", "Редактирование параметра для типа анализв").dialog("open");
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
if (Yii::app()->user->checkAccess('guideEditAnalyzerType')) {
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
        CHtml::link('Добавить', $this->createUrl('analysistypeparam/add/id/' . $model->analysis_type_id), [ 'class' => 'btn btn-primary', 'ajax' => array(
                'url' => $this->createUrl('analysistypeparam/add/id/' . $model->analysis_type_id),
                'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление параметра к списку").dialog("open"); return false;}',
            ),
        ]);
        ?>
    <?php } ?>

<?php } ?>

<?php if ($model->analysis_type_id > 0) { ?>
    <?php
    $this->widget('zii.widgets.grid.CGridView', [
        'id' => 'analysis-type-param-grid',
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
#                [
#            'name'=>'analysis_type_id',
#            'headerHtmlOptions' => array('style' => 'display:none'),
#            'htmlOptions' => array('style' => 'display:none'),        ],
            [
                'name' => 'analysis_param_id',
                'value' => '$data->analysisParams->name',
            ],
            [
                'name' => 'is_default',
                'value' => '$data->getBool($data->is_default)',
            ],
            'seq_number',
            [
                'class' => 'CButtonColumn',
                'deleteConfirmation' => "js:'Вы уверены, что хотите удалить параметр \'' + $(this).parent().parent().children(':nth-child(2)').text() + '\' из списка?'",
                'template' => $template,
                'buttons' => $buttons,
            ],
        ],
            ]
    );
    ?>
<?php } ?>
