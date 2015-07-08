<?php
/**
 * @var TreatmentController $this - Self instance
 */
$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_MedcardSearch');
$this->widget('Laboratory_Modal_PatientCreator');

$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_GREEN_JUSTIFIED,
    'items' => [
        'direction/active' => [
            'label' => 'Направления',
            'data-tab' => 'treatment-direction-grid-wrapper',
        ],
        'direction/repeat' => [
            'label' => 'Повторный забор образцов&nbsp;' . CHtml::tag('span', [
                    'class' => 'badge', 'id' => 'treatment-repeat-counts'
                ], Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_TREATMENT_REPEAT)),
            'data-tab' => 'treatment-repeated-grid-wrapper'
        ],
        'direction/create' => [
            'label' => 'Создать направление',
            'data-tab' => 'treatment-direction-creator-wrapper',
        ],
    ],
    'active' => 'direction/active',
]) ?>

<div class="treatment-table-wrapper table-wrapper">
    <hr>
    <div id="treatment-direction-grid-wrapper">
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Направления на анализ',
            'body' => $this->createWidget('GridTable', [
                'provider' => new Laboratory_Grid_Direction([
                    'status' => Laboratory_Direction::STATUS_TREATMENT_ROOM
                ])
            ]),
            'status' => Laboratory_Direction::STATUS_TREATMENT_ROOM
        ]) ?>
        <hr>
        <?php $this->widget('Panel', [
            'title' => 'Медицинские карты лаборатории',
            'body' => $this->createWidget('Laboratory_Widget_MedcardSearchEx'),
            'id' => 'treatment-laboratory-medcard-table-panel',
            'collapsible' => false
        ]) ?>
    </div>
    <div id="treatment-repeated-grid-wrapper" style="display: none;">
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Направления на повторный забор образца',
            'body' => $this->createWidget('GridTable', [
                'provider' => new Laboratory_Grid_Direction([
                    'status' => Laboratory_Direction::STATUS_TREATMENT_REPEAT
                ])
            ]),
            'status' => Laboratory_Direction::STATUS_TREATMENT_REPEAT
        ]) ?>
    </div>
    <div id="treatment-direction-creator-wrapper" style="display: none;">
        <?php $this->widget('Laboratory_Widget_PatientCreator') ?>
    </div>
</div>
<script>
$(document).ready(function() {
	$(document).on('barcode.captured', function(e, p) {
		Laboratory_DirectionTable_Widget.show(p.barcode);
	});
});
</script>
