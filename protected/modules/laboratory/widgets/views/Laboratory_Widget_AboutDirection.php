<?php
/**
 * @var $this Laboratory_Widget_AboutDirection - Instance of widget associated with that view file
 * @var $direction Laboratory_Direction - Row with information about direction
 * @var $analysis Laboratory_AnalysisType - Row with information about type of direction's analysis
 * @var $parameters array - Array with analysis type parameters
 * @var $samples array - Array with analysis type sample types
 */
$sendingDate = substr($direction->{"sending_date"}, 0, strpos($direction->{"sending_date"}, " "));
$sendingTime = substr($direction->{"sending_date"}, strpos($direction->{"sending_date"}, " ") + 1);
?>
<?= CHtml::openTag('div', [
	'class' => 'about-direction'
]) ?>
<?php $this->beginWidget('Panel', [
	'title' => 'Информация об образце',
	'id' => 'treatment-about-direction-analysis-panel',
	'collapsible' => true
]) ?>
<form class="direction-info-wrapper" style="font-size: 15px" action="<?= Yii::app()->createUrl("laboratory/direction/laboratory") ?>">
	<?= CHtml::hiddenField('Laboratory_Form_AboutDirection[direction_id]', $direction->{'id'}, [
		'id' => 'treatment-about-direction-id'
	]); ?>
	<?= CHtml::hiddenField('Laboratory_Form_AboutDirection[medcard_id]', $direction->{'medcard_id'}, [
		'id' => 'treatment-about-direction-medcard-id'
	]); ?>
	<div class="col-xs-12 no-padding">
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Состояние: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<span><?= Laboratory_Direction::listStatuses()[$direction->{'status'}] ?></span>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Требуемый анализ: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<span><?= trim($analysis->{'name'}).' ('.trim($analysis->{'short_name'}).')' ?></span>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Тип образца: </b></label>
			</div>
			<div class="col-xs-8 text-left sample-type-list-wrapper">
				<?= CHtml::dropDownList('Laboratory_Form_AboutDirection[sample_type_id]', $direction->{'sample_type_id'} ? $direction->{'sample_type_id'} : '-1', [ -1 => 'Нет' ] + CHtml::listData($samples, 'id', 'path'), [
					'class' => 'form-control sample-type-list'
				]) ?>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Параметры анализа: </b></label>
			</div>
			<div class="col-xs-8 text-left">
			<?php $this->widget('Laboratory_Widget_AnalysisParameterChecker', [
				'parameters' => $parameters,
				'name' => 'Laboratory_Form_AboutDirection[analysis_parameters][]'
			]) ?>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Дата взятия образца: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<div class="input-group">
					<input name="LAboutDirectionForm[sending_date]" style="margin-top: 0" data-provide="datepicker" data-date-language="ru-RU" data-date-format="yyyy-mm-dd" class="form-control" value="<?= $sendingDate ?>" title="" aria-describedby="basic-addon">
					<span class="input-group-addon" id="basic-addon" onclick="$(this).parent().children('input').val('<?= $sendingDate ?>')">
						<span class="glyphicon glyphicon-calendar" onmouseenter="$(this).tooltip('show')" data-placement="top" data-original-title="Восстановить"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Время взятия образца: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<div class="input-group">
					<input name="LAboutDirectionForm[sending_time]" readonly style="margin-top: 0" class="form-control" value="<?= $sendingTime ?>" title="" aria-describedby="basic-addon">
					<span class="input-group-addon" id="basic-addon">
						<span class="glyphicon glyphicon-time"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Комментарий: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<textarea name="LAboutDirectionForm[comment]" class="form-control" rows="6" style="resize: vertical" title=""><?= $direction->{"comment"} ?></textarea>
			</div>
		</div>
	</div>
</form>
<hr>
<div class="col-xs-12 no-padding text-center barcode-wrapper">
	<?= BarcodeGenerator::createGenerator($direction->{'barcode'})->getImage() ?>
</div>
<hr>
<div class="col-xs-12 text-center">
	<button id="send-to-laboratory-button" class="btn btn-default">
		<?php if ($direction->{'status'} == Laboratory_Direction::STATUS_TREATMENT_ROOM ||
			$direction->{'status'} == Laboratory_Direction::STATUS_TREATMENT_REPEAT): ?>
			<span class="glyphicon glyphicon-sort"></span> Передать в лабораторию
		<?php else: ?>
			<span class="glyphicon glyphicon-save"></span> Сохранить
		<?php endif ?>
	</button>
	<button id="print-barcode-button" class="btn btn-primary">
		<span class="glyphicon glyphicon-print"></span> Печать штрих-кода
	</button>
</div>
<?php $this->endWidget("Panel") ?>
<hr>
<?php $this->widget('AboutMedcard', [
	'medcard' => $direction->{'medcard_id'}
]) ?>
<?= CHtml::closeTag('div') ?>