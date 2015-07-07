<?php
/**
 * @var $this Laboratory_Widget_AnalysisResult
 * @var $direction LDirection
 * @var $analysis LAnalysis
 * @var $results LAnalysisResult[]
 * @var $allowed array
 */
$model = new Laboratory_Form_AnalysisResult();
$c = 0;
$form = $this->beginWidget('CActiveForm', [
	'action' => Yii::app()->createUrl('laboratory/laboratory/confirm')
]) ?>
<?= CHtml::activeHiddenField($model, 'id', [
	'value' => $direction->getAttribute('id')
]) ?>
<div class="row" style="font-size: 15px">
	<div class="col-xs-12">
		<?php foreach ($results as $result): ?>
			<?php if (!in_array($result->{'testid'}, $allowed)) { continue; } ?>
			<div class="col-xs-6" style="<?= (!($c++ % 2) ? 'border-right: 1px solid grey' : '' ) ?>">
				<div class="col-xs-1">
					[<?= (strlen($result->{'seq_number'}) == 1 ? '0'.$result->{'seq_number'} : $result->{'seq_number'}) ?>]
				</div>
				<div class="col-xs-4 text-right">
					<?= $result->{'testid'} ?>
				</div>
				<div class="col-xs-4">
					<?= CHtml::activeTextField($model, 'result['. $result->{'id'} .']', [
						'class' => 'form-control',
						'style' => 'margin-bottom: 5px',
						'value' => $result->{'val'}
					]) ?>
				</div>
				<div class="col-xs-2" style="font-size: 12px">
					<i><?= $result->{'units'} ?></i>
				</div>
				<?php if (!empty($result->{'comment'})): ?>
					<div class="col-xs-1" onmouseenter="$(this).tooltip('show')" title="" data-placement="right" data-original-title="<?= $result->{'comment'} ?>">
						<span class="glyphicon glyphicon-comment" style="color: #428ac9;"></span>
					</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>
</div>
<?php $this->endWidget('CActiveForm') ?>