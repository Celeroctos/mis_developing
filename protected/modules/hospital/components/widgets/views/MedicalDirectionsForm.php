<?php
/**
 * @var $form CActiveForm
 * @var $model CFormModel
 * @var mixed $currentMedcard
 * @var int $currentOmsId
 * @var int $currentDoctorId
 * @var array $wardsList
 */
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/engine/component/modules/module/modules/hospital/widgets/medical_directions_form.js"></script>
<div id="accordionD" class="accordion col-xs-12" >
<div class="accordion-group">
<div class="accordion-heading">
	<a href="#collapseD" data-parent="#accordionD" data-toggle="collapse" class="accordion-toggle" data-toggle="tooltip" data-placement="right" title="Здесь можно посмотреть направления пациента"><strong>Направления</strong></a>
</div>
<div class="accordion-body collapse in" id="collapseD">
	<div class="accordion-inner no-padding" id="doctor-direction-nav">
		<div class="directionsList overlayCont">
			<ul class="cont no-margin">
			</ul>
			<div class="btns">

				<ul class="nav nav-pills nav-justified">
					<li role="presentation">
						<a href="#doctor-schedule-on-hospitalization" role="tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">
							На госпитализацию
						</a>
					</li>
					<li role="presentation">
						<a href="#doctor-schedule-on-consultation" role="tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">
							На консультацию
						</a>
					</li>
					<li role="presentation">
						<a href="#doctor-schedule-on-direction" role="tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">
							На анализ
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade col-xs-12" id="doctor-schedule-on-hospitalization">
						<br>
						<?php $form = $this->beginWidget('CActiveForm', array(
							'id' => 'add-direction-form',
							'enableAjaxValidation' => true,
							'enableClientValidation' => true,
							'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/hospital/mdirections/add'),
							'htmlOptions' => array(
								'class' => 'form-horizontal col-xs-12',
								'role' => 'form'
							)
						));
						echo $form->hiddenField($model, 'omsId', array(
							'id' => 'directionOmsId',
							'value' => $currentOmsId
						)); echo $form->hiddenField($model, 'doctorId', array(
							'value' => $currentDoctorId
						)); echo $form->hiddenField($model, 'cardNumber', array(
							'value' => $currentMedcard
						)); ?>
						<div class="form-group col-xs-12">
							<?= $form->labelEx($model,'type', array(
								'class' => 'col-xs-5 control-label'
							)); ?>
							<div class="col-xs-7">
								<?= $form->dropDownList($model, 'type', array('Обычная', 'Срочная'), array(
									'class' => 'form-control'
								)); ?>
							</div>
						</div>
						<div class="form-group col-xs-12">
							<?= $form->labelEx($model,'isPregnant', array(
								'class' => 'col-xs-5 control-label'
							)); ?>
							<div class="col-xs-7">
								<?= $form->dropDownList($model, 'isPregnant', array('Нет', 'Да'), array(
									'class' => 'form-control'
								)); ?>
							</div>
						</div>
						<div class="form-group col-xs-12">
							<?= $form->labelEx($model,'wardId', array(
								'class' => 'col-xs-5 control-label'
							)); ?>
							<div class="col-xs-7">
								<?= $form->dropDownList($model, 'wardId', $wardsList, array(
									'class' => 'form-control'
								)); ?>
							</div>
						</div>
						<div class="form-group col-xs-12">
							<?= $form->labelEx($model,'pregnantTerm', array(
								'class' => 'col-xs-5 control-label'
							)); ?>
							<div class="col-xs-7">
								<?= $form->textField($model, 'pregnantTerm', array(
									'class' => 'form-control'
								)); ?>
							</div>
						</div>
						<hr>
						<div class="form-group btns">
							<button type="button" id="directionAddSubmit" class="btn btn-primary">Сохранить</button>
							<button type="button" id="directionAddClose" class="btn btn-default">Закрыть</button>
						</div>
						<br>
						<?php $this->endWidget(); ?>
					</div>
					<div class="tab-pane fade" id="doctor-schedule-on-consultation">
						<h4 class="text-center">Не реализовано</h4>
					</div>
					<div class="tab-pane fade" id="doctor-schedule-on-direction">
						<br>
						<?php $this->widget("application.modules.laboratory.widgets.DirectionCreator", [
							"controls" => [
								"direction-creator-cancel" => [
									"label" => "Отменить",
									"class" => "btn btn-default",
									"type" => "button",
									"style" => "margin-left: 10px"
								],
							],
							"defaults" => [
								"mis_medcard" => Yii::app()->getRequest()->getQuery("cardid")
							]
						]) ?>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>