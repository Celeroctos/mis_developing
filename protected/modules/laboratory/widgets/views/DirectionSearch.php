<?php
/**
 * @var $this DirectionSearch
 */
$this->widget("AutoForm", [
	"id" => "direction-search-form",
	"url" => Yii::app()->createUrl("laboratory/direction/search"),
	"model" => new LDirectionSearchForm(),
	"defaults" => [
		"widget" => $this->widget,
		"provider" => $this->provider,
		"config" => $this->config,
	]
]) ?>
<br>
<div class="col-xs-12 text-center">
	<button class="btn btn-success direction-search-button" style="width: 30%">
		<span class="glyphicon glyphicon-search"></span> Найти
	</button>
</div>