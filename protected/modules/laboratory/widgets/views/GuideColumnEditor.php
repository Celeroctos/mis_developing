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
			<? if (count($this->columns) == 0): ?>
				<div><? $this->widget("AutoForm", [ "model" => $this->default ]) ?><hr></div>
			<? endif; ?>
			<? foreach ($this->columns as $column): ?>
				<div class="guide-column-handle">
					<a href="javascript:void(0)">
						<span class="glyphicon glyphicon-remove guide-remove-column" style="color: #af1010"></span>
					</a>
					<? $this->widget("AutoForm", [ "model" => $column ]) ?>
					<hr>
				</div>
			<? endforeach; ?>
		</div>
		<a href="javascript:void(0)" id="guide-append-column">
			<span class="glyphicon glyphicon-plus"></span>
		</a>
	</div>
</div>