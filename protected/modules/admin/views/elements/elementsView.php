<h4>Краткая справка</h4>
<p>Раздел предназначен для редактирования содержания медицинской карты для рабочего места врача. Карта у врача разбита на категории (раскрывающиеся списки), внутри них имеются управляющие элементы, которые могут представлять собой в том числе выбор значения из справочника.
    При формировании шаблона карты требуется определить группы, поля карты, справочники и привязать последние к определённым полям. Справочники при необходимости можно дополнять значениями.
</p>
<?php $this->widget('application.components.widgets.DoctorCardTabMenu') ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin/elements.js"></script>
<table id="elements"></table>
<div id="elementsPager"></div>
<div class="btn-group default-margin-top">
    <button type="button" class="btn btn-default" id="addElement">Добавить запись</button>
    <button type="button" class="btn btn-default" id="editElement">Редактировать выбранную запись</button>
    <button type="button" class="btn btn-default" id="deleteElement">Удалить выбранные</button>
</div>
<div class="modal fade" id="addElementPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Добавить элемент управления</h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'focus' => array($model,'name'),
                'id' => 'element-add-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/index.php/admin/elements/add'),
                'htmlOptions' => array(
                    'class' => 'form-horizontal col-xs-12',
                    'role' => 'form'
                )
            ));
            ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'type', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'type', $typesList, array(
                                    'id' => 'type',
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo $form->error($model,'type'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'categorieId', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'categorieId', $categoriesList, array(
                                    'id' => 'categorieId',
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo $form->error($model,'categorieId'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'label', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->textField($model,'label', array(
                                    'id' => 'label',
                                    'class' => 'form-control',
                                    'placeholder' => 'Метка'
                                )); ?>
                                <?php echo $form->error($model,'label'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'guideId', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'guideId', $guidesList, array(
                                    'id' => 'guideId',
                                    'class' => 'form-control',
                                    'disabled' => true,
                                    'options' => array('selected' => -1)
                                )); ?>
                                <?php echo $form->error($model,'guideId'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <?php echo CHtml::ajaxSubmitButton(
                    'Добавить',
                    CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/index.php/admin/elements/add'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                                $("#element-add-form").trigger("success", [data, textStatus, jqXHR])
                            }'
                    ),
                    array(
                        'class' => 'btn btn-primary'
                    )
                ); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="editElementPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Редактировать элемент управления</h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'focus' => array($model,'name'),
                'id' => 'element-edit-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/index.php/admin/elements/edit'),
                'htmlOptions' => array(
                    'class' => 'form-horizontal col-xs-12',
                    'role' => 'form'
                )
            ));
            ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <?php echo $form->hiddenField($model,'id', array(
                                'id' => 'id',
                                'class' => 'form-control'
                            )); ?>
                            <?php echo $form->labelEx($model,'type', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'type', $typesList, array(
                                    'id' => 'type',
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo $form->error($model,'type'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'categorieId', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'categorieId', $categoriesList, array(
                                    'id' => 'categorieId',
                                    'class' => 'form-control'
                                )); ?>
                                <?php echo $form->error($model,'categorieId'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'label', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->textField($model,'label', array(
                                    'id' => 'label',
                                    'class' => 'form-control',
                                    'placeholder' => 'Метка'
                                )); ?>
                                <?php echo $form->error($model,'label'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'guideId', array(
                                'class' => 'col-xs-3 control-label'
                            )); ?>
                            <div class="col-xs-9">
                                <?php echo $form->dropDownList($model, 'guideId', $guidesList, array(
                                    'id' => 'guideId',
                                    'class' => 'form-control',
                                    'disabled' => true
                                )); ?>
                                <?php echo $form->error($model,'guideId'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <?php echo CHtml::ajaxSubmitButton(
                    'Отредактировать',
                    CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/index.php/admin/elements/edit'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                                $("#element-edit-form").trigger("success", [data, textStatus, jqXHR])
                            }'
                    ),
                    array(
                        'class' => 'btn btn-primary'
                    )
                ); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="errorAddElementPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ошибка!</h4>
            </div>
            <div class="modal-body">
                <h4>При заполнении формы возникли следующие ошибки:</h4>
                <div class="row">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>