<?php
/**
 * @var $this AnalysisResult
 * @var $direction LDirection
 * @var $analysis LAnalysis
 * @var $results LAnalysisResult[]
 */
$c = 0;
?>
<div class="row" style="font-size: 15px">
	<div class="col-xs-12">
		<?php foreach ($results as $result): ?>
			<div class="col-xs-6" style="<?= (!($c++ % 2) ? 'border-right: 1px solid black' : '' ) ?>">
				<div class="col-xs-1">
					[<?= (strlen($result->{'seq_number'}) == 1 ? '0'.$result->{'seq_number'} : $result->{'seq_number'}) ?>]
				</div>
				<div class="col-xs-4 text-right">
					<?= $result->{'testid'} ?>
				</div>
				<div class="col-xs-4">
					<?= CHtml::textField('', $result->{'val'}, [
						'class' => 'form-control'
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