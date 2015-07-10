<?php
/**
 * @var TreatmentController $this - Self instance
 */
$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_MedcardSearch');

$this->widget('TabMenu', [
    'style' => TabMenu::STYLE_GREEN_JUSTIFIED,
    'items' => [
        'direction-active' => [
            'label' => 'Направления',
            'data-tab' => 'laboratory-container-direction-active',
        ],
        'direction-repeat' => [
            'label' => 'Повторный забор образцов&nbsp;' . CHtml::tag('span', [
                    'class' => 'badge', 'id' => 'treatment-repeat-counts'
                ], Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_TREATMENT_REPEAT)),
            'data-tab' => 'laboratory-container-direction-repeat'
        ],
        'direction-create' => [
            'label' => 'Создать направление',
            'data-tab' => 'laboratory-container-direction-create',
        ],
    ],
    'active' => 'direction-active',
]) ?>

<div class="laboratory-table-wrapper table-wrapper">
    <hr>
    <div id="laboratory-container-direction-active">
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Направления на анализ',
            'status' => Laboratory_Direction::STATUS_TREATMENT_ROOM
        ]) ?>
    </div>
    <div id="laboratory-container-direction-repeat" style="display: none;">
        <?php $this->widget('Laboratory_Widget_DirectionPanel', [
            'title' => 'Направления на повторный забор образца',
            'status' => Laboratory_Direction::STATUS_TREATMENT_REPEAT,
        ]) ?>
    </div>
    <div id="laboratory-container-direction-create" style="display: none;">
        <?php $this->widget('Laboratory_Widget_PatientCreator') ?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(document).on('barcode.captured', function(e, p) {
		Laboratory_DirectionTable_Widget.show(p.barcode);
	});
});
</script>
