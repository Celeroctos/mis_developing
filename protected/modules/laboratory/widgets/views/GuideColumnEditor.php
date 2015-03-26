<?php

/**
 * @var $this GuideColumnEditor - Widget's instance
 */

$this->widget("AutoForm", [
	"url" => Yii::app()->getBaseUrl()."/laboratory/guide/update",
	"model" => $this->model,
	"id" => "add-guide-form"
]);

?>

<div class="panel panel-default col-xs-10 col-md-offset-1 col-xs-offset-1">
	<div class="panel-heading" style="text-align: right">
		<div class="column-container" style="padding-top: 5px">
			<?php if (count($this->columns) == 0): ?>
				<div><?php $this->widget("AutoForm", [ "model" => $this->default ]) ?><hr></div>
			<?php endif; ?>
			<?php foreach ($this->columns as $column): ?>
				<div class="guide-column-handle">
					<a href="javascript:void(0)">
						<span class="glyphicon glyphicon-remove guide-remove-column" style="color: #af1010"></span>
					</a>
					<?php $this->widget("AutoForm", [ "model" => $column ]) ?>
					<hr>
				</div>
			<?php endforeach; ?>
		</div>
		<a href="javascript:void(0)" id="guide-append-column">
			<span class="glyphicon glyphicon-plus"></span>
		</a>
	</div>
</div>