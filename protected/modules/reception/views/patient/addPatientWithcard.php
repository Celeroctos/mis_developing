<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/chooser.js" ></script>
<!--<script type="text/javascript" src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/js/datecontrol.js"></script>-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/reception/searchAddPatient.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cardnumberGenerator.js" ></script>
<script type="text/javascript">
	globalVariables.medcardData = {
		'prefix' : '<?php echo $medcardNumberPrefix; ?>',
		'postfix' : '<?php echo $medcardNumberPostfix; ?>',
		'number' : '<?php echo $medcardNumber; ?>'
	}
</script>
<?php if(Yii::app()->user->checkAccess('addPatient')) { ?>
<h4>Регистрация / перерегистрация карты к существующему пациенту (<?php echo $regPoint; ?> год)</h4>
<p class="text-left">
    Заполните поля формы, чтобы зарегистрировать / перерегистрировать карту пациента <span class="text-danger bold">(<?php echo $fio; ?>, полис №<?php echo $policy_number; ?>)</span>
</p>
<?php if(isset($tasuStatus) && !$tasuStatus) { ?>
<p class="bold text-danger noTasuConnection">ВНИМАНИЕ! ТАСУ недоступна: внимательно проверьте реквизиты перед сохранением данных.</p>
<?php } ?>
<div class="row default-padding">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'patient-withcard-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/reception/patient/addcard'),
        'htmlOptions' => array(
            'class' => 'form-horizontal col-xs-12',
            'role' => 'form'
        )
    ));
    ?>
	<div class="newMedcardNumberBlock" id="kdoReg">
		<strong>Пациенту будет выдан номер карты <span class="bigger"><?php echo $newCardNumber; ?></span></strong>
	</div>
    <div class="row">
        <div class="col-xs-6">
            <?php echo $form->hiddenField($model,'mediateId', array(
                'id' => 'mediateId'
            )); ?>
            <?php echo $form->hiddenField($model,'policy', array(
                'id' => 'policy',
                'class' => 'form-control'
            )); ?>
			 <?php echo $form->hiddenField($model,'cardNumber', array(
                'id' => 'cardNumber',
                'class' => 'form-control'
            )); ?>
            <p>Данные медицинской карты:</p>
            <?php $this->widget('application.modules.reception.components.widgets.MedcardFormWidget', array(
                'form' => $form,
                'model' => $model,
                'privilegesList' => $privilegesList,
                'showEditIcon' => 1,
                'template' => 'application.modules.reception.components.widgets.views.MedcardFormWidget'
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="add-patient-submit">
            <?php echo CHtml::ajaxSubmitButton(
                'Добавить',
                CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/reception/patient/addcard'),
                array(
                    'success' => 'function(data, textStatus, jqXHR) {
                        $("#patient-withcard-form").trigger("success", [data, textStatus, jqXHR])
                    }',
					'beforeSend' => 'function(jqXHR, settings) {
						 $(".add-patient-submit input").trigger("begin")
					}'
                ),
                array(
                    'class' => 'btn btn-success'
                )
            ); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<?php } ?>
<div class="modal fade error-popup" id="errorAddPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ошибка!</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="successAddPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Успешно!</h4>
            </div>

            <!--
            <div class="modal-body">
                <div class="row">
                    <p>Поздравляем, вы успешно добавили нового пациента и создали для него первую карту. Впоследствии, Вы можете добавлять новые карты при <?php echo CHtml::link('поиске пациента', array('/reception/patient/viewsearch')) ?> или <?php echo CHtml::link('записать', array('#'), array('class' => 'writePatient')) ?> добавленного пациента на приём</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="writePatientBtn">Записать пациента</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
            -->
            <div class="modal-body">
                <div class="row">
                    <p>Поздравляем, вы успешно создали карту.</p>
                    <p>Номер карты: <span id="successCardNumber"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="printPatientBtn">Печать титульного листа</button>
                <button type="button" class="btn btn-success" id="writePatientBtn">Записать пациента</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>
<?php $this->widget('application.modules.reception.components.widgets.MedcardFormWidget', array(
    'form' => $form,
    'model' => $model,
    'privilegesList' => $privilegesList,
    'showEditIcon' => 1,
    'template' => 'application.modules.reception.components.widgets.views.addressEditPopup'
)); ?>