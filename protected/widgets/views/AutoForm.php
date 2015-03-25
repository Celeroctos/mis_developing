<?
/**
 * @var AutoForm $this - Form widget instance
 * @var CActiveForm $form - Form widget
 * @var FormModel $model - Form model
 */

$form = $this->beginWidget('CActiveForm', [
	'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'action' => CHtml::normalizeUrl($this->url),
	'id' => $this->id,
    'htmlOptions' => [
        'class' => 'form-horizontal col-xs-12',
        'role' => 'form',
        'data-form' => get_class($this->model),
        'data-widget' => get_class($this)
    ]
]); ?>

<? foreach ($model->getConfig() as $key => $value): ?>
    <div class="form-group <?= $this->isHidden($key) ? "hidden" : "" ?>">
		<? if ($this->labels == true): ?>
			<? if (!$this->checkType($key, "Hidden")) {
				echo $form->labelEx($model, $key, array(
					'class' => 'col-xs-4 control-label'
				));
			} ?>
			<div class="col-xs-7">
				<?= $this->renderField($form, $key); ?>
			</div>
		<? else: ?>
			<div class="col-xs-10 col-md-10 col-lg-10 col-xs-offset-1 col-md-offset-1 col-lg-offset-1">
				<?= $this->renderField($form, $key); ?>
			</div>
		<? endif ?>
		<div class="col-xs-1">
		</div>
    </div>
<? endforeach; ?>

<? $this->endWidget(); ?>