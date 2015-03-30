<div id="helperIcon"></div>
<div class="col-xs-12 row">
	<ul class="nav nav-tabs" id="hospitalizationNavbar">
	  <li role="navigation" class="active">
		<a href="#queue" aria-controls="queue" role="tab" data-toggle="tab" id="queueTab">Очередь</a>
		<span class="tabmark" id="queueTabmark">
            <span class="roundedLabel"></span>
            <span class="roundedLabelText"></span>
        </span>
	  </li>
	  <li role="navigation">
		<a href="#comission" aria-controls="comission" role="tab" data-toggle="tab" id="comissionTab">Комиссия на госпитализацию</a>
        <span class="tabmark" id="comissionTabmark">
            <span class="roundedLabel"></span>
            <span class="roundedLabelText"></span>
        </span>
	  </li>
	  <li role="navigation">
		<a href="#hospitalization" aria-controls="hospitalization" role="tab" data-toggle="tab" id="hospitalizationTab">Госпитализация</a>
        <span class="tabmark" id="hospitalizationTabmark">
            <span class="roundedLabel"></span>
            <span class="roundedLabelText"></span>
        </span>
	  </li>
	  <li role="navigation">
		<a href="#history" aria-controls="history" role="tab" data-toggle="tab" id="historyTab">История приёмов</a>
        <span class="tabmark" id="historyTabmark">
            <span class="roundedLabel"></span>
            <span class="roundedLabelText"></span>
        </span>
	  </li>
	</ul>
</div>
<div class="row col-xs-12 tableBlock">
	<div class="hospitalizationSide col-xs-3">
		<div id="sideCalendar"></div>
	</div>
	<div class="hospitalizationTable col-xs-8">
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="queue">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
			</div>
			<div role="tabpanel" class="tab-pane" id="comission">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
			</div>
			<div role="tabpanel" class="tab-pane" id="hospitalization">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
			</div>
			<div role="tabpanel" class="tab-pane" id="history">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
			</div>
		</div>
	</div>
</div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal col-xs-12',
        'role' => 'form'
    )
));
?>
<div class="modal fade error-popup" id="changeHospitalizationDatePopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Определение даты госпитализации</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php echo $form->hiddenField($model, 'id', array(
                        'id' => 'directionId',
                        'class' => 'form-control'
                    )); ?>
                    <?php echo $form->hiddenField($model, 'grid_id', array(
                        'id' => 'gridId',
                        'class' => 'form-control'
                    )); ?>
                    <div class="form-group col-xs-12">
                        <?=$form->labelEx($model,'hospitalization_date', array(
                            'class' => 'col-xs-4'
                        )); ?>
                        <div class="col-xs-8">
                            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model' => $model,
                                'name' => 'hospitalization_date',
                                'attribute' => 'hospitalization_date',
                                'language' => 'ru',
                                'flat' => true,
                                'htmlOptions' => array(
                                    'id' => 'modal_hospitalization_date_datepicker'
                                ),
                                'options' => array(
                                    'showOn' => 'focus',
                                    'dateFormat' => 'dd.mm.yy',
                                    'showOtherMonths' => true,
                                    'selectOtherMonths' => true,
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'showButtonPanel' => true,
                                )
                            )); ?>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <?=$form->labelEx($model,'write_type', array(
                            'class' => 'col-xs-4'
                        )); ?>
                        <div class="col-xs-7">
                            <?php echo $form->dropDownList($model, 'write_type', $writeTypes, array(
                                'id' => 'writeType',
                                'class' => 'form-control'
                            )); ?>
                        </div>
                    </div>
                    <div id="refuseCommentContainer">
                        <?php echo $form->textArea($model, 'refuse_comment', array(
                            'class' => 'form-control',
                            'id' => 'refuseComment'
                        )); ?>
                        <?php echo CHtml::ajaxSubmitButton(
                            'Подтвердить',
                             CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/hospitalization/dismisshospitalization'),
                             array(
                                 'success' => 'function(data, textStatus, jqXHR) {
                                      var data = $.parseJSON(data);
                                      $("#" + data.gridId).trigger("reload");
                                  }',
                                 'beforeSend' => 'function(jqXHR, settings) { }'
                             ),
                            array(
                                'class' => 'btn btn-success default-margin-top'
                            )
                        ); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo CHtml::ajaxSubmitButton(
                    'Сохранить',
                    CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/hospitalization/changehospitalizationdate'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                            var data = $.parseJSON(data);
                            $("#" + data.gridId).trigger("reload");
                        }',
                        'beforeSend' => 'function(jqXHR, settings) { }'
                    ),
                    array(
                        'class' => 'btn btn-success'
                    )
                ); ?>
                <?php echo CHtml::button(
                    'Отказ от госпитализации',
                   /* CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/hospitalization/dismisshospitalization'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                             var data = $.parseJSON(data);
                             $("#" + data.gridId).trigger("reload");
                         }',
                        'beforeSend' => 'function(jqXHR, settings) { }'
                    ), */
                    array(
                        'class' => 'btn btn-danger',
                        'id' => 'dismissHospitalizationBtn'
                    )
                ); ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<div class="modal fade error-popup" id="MedicalExamPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Осмотр пациента</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $counter = 0;
                    foreach ($templatesList as $key => $template) {
                        ?>
                        <div>
                            <?php
                            $this->widget('application.modules.doctors.components.widgets.CategorieViewWidget', array(
                                'currentPatient' => $currentPatient,
                                'templateType' => 0,
                                'templateId' => $template['id'],
                                'withoutSave' => 0,
                                'greetingId' => $currentSheduleId,
                                'canEditMedcard' => $canEditMedcard,
                                'medcard' => $medcard,
                                'currentDate' => $currentDate,
                                'templatePrefix' => 'a' . $template['id'],
                                'medcardRecordId' => $medcardRecordId,
                                'isActiveTemplate' => $counter == 0
                            )); ?>
                        </div>
                        <?php
                        $counter++;
                    } ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo CHtml::ajaxSubmitButton(
                    'Закончить осмотр',
                    CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/hospitalization/changehospitalizationdate'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                            var data = $.parseJSON(data);
                            $("#" + data.gridId).trigger("reload");
                        }',
                        'beforeSend' => 'function(jqXHR, settings) { }'
                    ),
                    array(
                        'class' => 'btn btn-success'
                    )
                ); ?>
                <?php echo CHtml::ajaxSubmitButton(
                    'Отказать в госпитализации',
                    CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/hospitalization/dismisshospitalization'),
                    array(
                        'success' => 'function(data, textStatus, jqXHR) {
                             var data = $.parseJSON(data);
                             $("#" + data.gridId).trigger("reload");
                         }',
                        'beforeSend' => 'function(jqXHR, settings) { }'
                    ),
                    array(
                        'class' => 'btn btn-danger'
                    )
                ); ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>