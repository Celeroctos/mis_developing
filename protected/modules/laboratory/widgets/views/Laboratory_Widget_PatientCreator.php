<?php
/**
 * @var Laboratory_Widget_PatientCreator $this - Self widget instance
 * @var string $number - Generated card number
 */
?>
<div class="laboratory-wrapper-patient-creator">
<form name="PropertyForm" class="form-horizontal">
<input id="laboratory-medcard-number" type="hidden" data-cleanup="false">
<div class="row">
	<div class="col-xs-6 text-center">
		<?php $this->widget('Panel', [
			'title' => 'Медицинская карта',
			'body' => $this->createWidget('AutoForm', [
				'model' => new Laboratory_Form_Medcard('treatment', [
					'card_number' => $number
				])
			]),
            'controls' => [
                'laboratory-patient-creator-question' => [
                    'label' => 'Если во время сохранения полученный номер карты будет использован другим врачем, то номер будет сгенерирован заново',
                    'icon' => 'glyphicon glyphicon-paperclip',
                ],
            ],
            'panelClass' => 'panel panel-default laboratory-panel-patient-creator-medcard',
			'headingClass' => 'panel-heading panel-heading-narrow row no-margin',
			'collapsible' => true,
			'controlMode' => ControlMenu::MODE_ICON,
		]) ?>
		<?php $this->widget('Panel', [
			'title' => 'Пациент',
			'body' => $this->createWidget('AutoForm', [
				'model' => new Laboratory_Form_Patient('treatment')
			]),
			'headingClass' => 'panel-heading panel-heading-narrow row no-margin',
			'collapsible' => true,
			'controlMode' => ControlMenu::MODE_NONE
		]) ?>
		<?php $this->widget('CheckPanel', [
			'title' => 'Документ',
			'body' => $this->createWidget('AutoForm', [
				'model' => new Laboratory_Form_Document('treatment')
			]),
			'name' => 'PropertyForm[passport]',
			'headingClass' => 'panel-heading panel-heading-narrow row no-margin',
			'controlMode' => ControlMenu::MODE_NONE,
			'checked' => false
		]) ?>
		<?php $this->widget('CheckPanel', [
			'title' => 'Страховой полис',
			'body' => $this->createWidget('AutoForm', [
				'model' => new Laboratory_Form_Policy('treatment')
			]),
			'name' => 'PropertyForm[policy]',
			'headingClass' => 'panel-heading panel-heading-narrow row no-margin',
			'controlMode' => ControlMenu::MODE_NONE,
			'checked' => false
		]) ?>
	</div>
	<div class="col-xs-6 text-center">
		<?php $this->widget('Panel', [
			'title' => 'Направление',
			'body' => $this->createWidget('Laboratory_Widget_DirectionCreator', [
				'disableControls' => true
			]),
			'headingClass' => 'panel-heading panel-heading-narrow row no-margin',
			'collapsible' => true,
			'controlMode' => ControlMenu::MODE_NONE
		]) ?>
        <hr>
        <div class="col-xs-12 no-padding">
            <div class="col-xs-6 text-left no-padding">
                <?php $this->widget('ControlMenu', [
                    'controls' => [
                        'laboratory-button-save-patient' => [
                            'label' => 'Сохранить',
                            'class' => 'btn btn-primary',
                            'icon' => 'glyphicon glyphicon-floppy-disk',
                            'type' => 'button',
                        ],
                    ],
                    'mode' => ControlMenu::MODE_BUTTON_LG,
                ]) ?>
            </div>
            <div class="col-xs-6 text-right no-padding">
                <?php $this->widget('ControlMenu', [
                    'controls' => [
                        'laboratory-button-find-medcard' => [
                            'label' => 'ЛКП',
                            'class' => 'btn btn-default',
                            'icon' => 'glyphicon glyphicon-search',
                            'type' => 'button',
                            'data-toggle' => 'modal',
                            'data-target' => '#laboratory-modal-patient-search',
                        ],
                        'laboratory-button-find-patient' => [
                            'label' => 'ЭМК',
                            'class' => 'btn btn-success',
                            'icon' => 'glyphicon glyphicon-search',
                            'type' => 'button',
                            'data-toggle' => 'modal',
                            'data-target' => '#laboratory-modal-medcard-search',
                        ],
                    ],
                    'mode' => ControlMenu::MODE_GROUP,
                ]) ?>
            </div>
        </div>
	</div>
</div>
</form>
</div>