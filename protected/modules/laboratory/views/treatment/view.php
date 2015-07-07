<?php
/**
 * @var TreatmentController $this - Self instance
 */
$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_MedcardSearch');
$this->widget('Laboratory_Modal_PatientCreator');
?>
<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded treatment-header-wrapper-active" data-tab="#treatment-direction-grid-wrapper" type="button">
				<span>Направления</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#treatment-repeated-grid-wrapper" type="button">
				<span>Повторный забор образцов</span>
				<span class="badge" id="treatment-repeat-counts">
					<?= LDirection::model()->getCountOf(LDirection::STATUS_TREATMENT_REPEAT) ?>
				</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button id="header-register-direction-button" class="btn btn-default btn-block treatment-header-rounded" type="button" data-target="#laboratory-modal-patient-creator" aria-expanded="false">
				<span>Создать направление</span>
			</button>
		</div>
	</div>
	<div class="treatment-table-wrapper table-wrapper">
		<hr>
		<div id="treatment-direction-grid-wrapper">
			<?php $this->widget('Laboratory_Widget_DirectionPanel', [
				'title' => 'Направления на анализ',
				'body' => $this->createWidget('GridTable', [
					'provider' => new Laboratory_Grid_Direction([
						'status' => LDirection::STATUS_TREATMENT_ROOM
					])
				]),
				'status' => LDirection::STATUS_TREATMENT_ROOM
			]) ?>
		</div>
		<div id="treatment-repeated-grid-wrapper" class="no-display">
			<?php $this->widget('Laboratory_Widget_DirectionPanel', [
				'title' => 'Направления на повторный забор образца',
				'body' => $this->createWidget('GridTable', [
					'provider' => new Laboratory_Grid_Direction([
						'status' => LDirection::STATUS_TREATMENT_REPEAT
					])
				]),
				'status' => LDirection::STATUS_TREATMENT_REPEAT
			]) ?>
		</div>
	</div>
	<hr>
	<?php $this->widget('Panel', [
		'title' => 'Медицинские карты лаборатории',
		'body' => $this->createWidget('Laboratory_Widget_MedcardSearchEx'),
		'id' => 'treatment-laboratory-medcard-table-panel',
		'collapsible' => false
	]) ?>
</div>
<script>
$(document).ready(function() {
	$(document).on('barcode.captured', function(e, p) {
		Laboratory_DirectionTable_Widget.show(p.barcode);
	});
});
</script>
