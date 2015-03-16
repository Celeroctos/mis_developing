<script type="text/javascript">
    globalVariables.guideEdit = '<?php echo Yii::app()->user->checkAccess('guideEditAnalysisSample'); ?>';</script>

<h4>Справочники лаборатории</h4>
<?php
$this->widget('application.modules.guides.components.widgets.LaboratoryTabMenu', array());
?>
<?php
$this->pageTitle = 'Типы образцов для анализов';
?>
<h4>Типы образцов для анализов</h4>

<?php if (Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-danger">
        <?= Yii::app()->user->getFlash('error'); ?>
        <?= Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php
$updateDialog = <<<'EOT'
function() {
	var url = $(this).attr('href');
    $.get(url, function(r){
        $("#update").html(r).dialog("open");
		$("#DialogCRUDForm").html(r).dialog("option", "title", "Редактирование типа образца для анализа").dialog("open");
    });
    return false;
}
EOT;
?>

<?php 
$template = '';
if (Yii::app()->user->checkAccess('guideEditAnalysisSample')) { 
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
<?=
CHtml::link('Добавить', $this->createUrl('#'), [ 'class' => 'btn btn-primary', 'ajax' => array(
        'url' => $this->createUrl('create'),
        'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление типа образца для анализа").dialog("open"); return false;}',
    ),
]);
?>
<?php } ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'DialogCRUDForm', 'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'height' => 'auto',
        'resizable' => 'false',
    ),
        )
);
        $this->endWidget();
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'analysis-param-grid',
    'ajaxUpdate' => false,
    'ajaxType'=>'POST',
    'dataProvider' => $model->search(),
//	'filter'=>$model,
    'itemsCssClass' => 'table table-bordered',
    'pager' => [
        'class' => 'CLinkPager',
        'selectedPageCssClass' => 'active',
        'header' => '',
        'htmlOptions' => [
            'class' => 'pagination',
        ]
    ],
    'columns' => array(
#		'id',
        [
            'name' => 'type',
            'headerHtmlOptions' => [
                'class' => 'col-md-4',
            ],
        ],
        [
            'name' => 'subtype',
            'headerHtmlOptions' => [
                'class' => 'col-md-4',
            ],
        ],
#		'comment',
        array(
            'class' => 'CButtonColumn',
            'deleteConfirmation' => "js:'Вы уверены, что хотите удалить тип образца для анализа \'' + $(this).parent().parent().children(':nth-child(1)').text() + '\'?'",
            'template' => $template,
            'buttons' => $buttons,
        ),
    ),
));
?>
      