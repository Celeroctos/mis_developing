<?php
/**
 * @var Laboratory_Widget_AboutMedcard $this
 * @var int $age
 * @var mixed $medcard
 * @var mixed $patient
 * @var mixed $address
 * @var mixed $registerAddress
 */
?>

<div class="medcard-viewer">
<?= Html::hiddenField('', $patient->id, [ 'id' => 'laboratory-about-medcard-patient-id' ]) ?>
<?php $this->widget('Panel', [
    'title' => 'Реквизитная информация',
    'controls' => [
        'laboratory-about-medcard-panel-edit-button' => [
            'icon' => 'glyphicon glyphicon-pencil',
            'class' => 'btn btn-default btn-sm',
            'label' => 'Редактировать',
            'onclick' => '',
        ],
        'panel-update-button' => [
            'icon' => 'glyphicon glyphicon-refresh',
            'onclick' => '$(this).panel("update")',
            'label' => 'Обновить',
            'class' => 'btn btn-default'
        ],
    ],
    'body' => $this->createWidget('Laboratory_Widget_AboutPatient', [
        'medcard' => $medcard,
        'patient' => $patient,
        'address' => $address,
    ]),
    'titleWrapperClass' => 'col-xs-6 text-left no-padding',
    'controlsWrapperClass' => 'col-xs-6 text-right no-padding',
    'controlMode' => ControlMenu::MODE_BUTTON_SM,
]) ?>
<?php $this->widget('Panel', [
	'title' => 'Направления',
	'id' => 'treatment-direction-history-panel',
	'body' => $this->createWidget('Laboratory_Widget_DirectionHistory', [
		'medcard' => $this->medcard
	]),
	'controls' => [
		'panel-update-button' => [
			'icon' => 'glyphicon glyphicon-refresh',
			'class' => 'btn btn-default btn-sm',
			'label' => 'Обновить',
			'onclick' => '$(this).panel("update")'
		]
	],
	'collapsible' => true
]); ?>
</div>