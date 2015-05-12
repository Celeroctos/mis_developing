<?php
/**
 * @var $this AnalyzerQueue
 */
?>
<hr>
<div class="col-xs-12 no-padding">
	<div class="analyzer-queue-container">
		<h4 class="text-center">Направления отсутствуют</h4>
	</div>
	<hr>
	<div class="col-xs-6 no-padding text-left">
		<?php $this->widget("ControlMenu", [
			"controls" => [
				"analyzer-task-start" => [
					"label" => "Начать",
					"icon" => "glyphicon glyphicon-play",
					"class" => "btn btn-default btn-sm",
				],
				"analyzer-task-stop" => [
					"label" => "Остановить",
					"icon" => "glyphicon glyphicon-stop",
					"class" => "btn btn-danger btn-sm",
				],
			],
			"mode" => ControlMenu::MODE_BUTTON
		]) ?>
	</div>
	<div class="col-xs-6 no-padding text-right">
		<?php $this->widget("ControlMenu", [
			"controls" => [
				"analyzer-task-start" => [
					"label" => "Очистить",
					"icon" => "glyphicon glyphicon-refresh",
					"class" => "btn btn-warning btn-sm",
				],
			],
			"mode" => ControlMenu::MODE_BUTTON
		]) ?>
	</div>
</div>
