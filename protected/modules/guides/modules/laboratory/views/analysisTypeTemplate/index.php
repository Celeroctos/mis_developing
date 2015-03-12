<script type="text/javascript">
    globalVariables.guideEdit = '<?php echo Yii::app()->user->checkAccess('guideEditAnalysisTypeTemplate'); ?>';</script>

<h4>Справочники лаборатории</h4>
<?php
$this->widget('application.modules.guides.components.widgets.LaboratoryTabMenu', array());
?>
<?php
$this->pageTitle = 'Шаблоны типов анализов';
?>
<h4>Шаблоны типов анализов</h4>

<?php if (Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-danger">
        <?= Yii::app()->user->getFlash('error'); ?>
        <?= Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>


    <div class="row">
        <div class="col-xs-6">
        <h6>Выберите тип анализа:</h6>
        
            <?php echo CHtml::DropDownList('SelectType','id',AnalysisType::getAnalysisTypeListData('insert'),
            array(
                'onchange'=>'alert(document.getElementById("SelectType").value);
                if(document.getElementById("SelectType").value > 0) 
                    document.getElementById("AddButton").disabled=""; 
                else 
                    document.getElementById("AddButton").disabled="disabled";',
    )
); ?>
        </div>
        </div>

    <div class="row">
        </div>
    <div class="row">
        <div class="col-xs-3">
    <?php 
echo CHtml::ajaxButton('Выбор', $this->createUrl('condupdate'), 
    array(
    'type' => 'POST',
    'update'=>'#analysis-param-grid',
    'data' => array(
        'SelectType'=>'js:document.getElementById("SelectType").value',
        )
        )
        );
    ?>    
        </div>
</div>

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
    if (Yii::app()->user->checkAccess('guideEditAnalysisType')) { 
?>
    <div class="row">
        </div>

    <div class="row">
        <div class="col-xs-3">
<?php
#    if ($model->analysis_type_id > 0) { 
?>
<?=
CHtml::ajaxLink('Добавить', $this->createUrl('analysistypetemplate/create/analysis_type_id/'.$model->analysis_type_id), 
array(
        'url' => $this->createUrl('analysistypetemplate/create/analysis_type_id/'.$analysis_type_id),
        'success' => 'js:function(r){$("#DialogCRUDForm").html(r).dialog("option", "title", "Добавление параметра к шаблону типа анализа").dialog("open"); return false;}',
    ),
[ 
'id' => 'AddButton',
'disabled'=>"disabled",
'class' => 'btn btn-primary', 
]);
?>
<?php
#    } 
?>
        </div>
</div>
<?php
    } 
?>

<?php
$this->renderPartial('grid', 
array(
'model' => $model,
'dataProvider'=>$dataProvider,
'analysistypesList' => $analysistypesList)
);      
?>
