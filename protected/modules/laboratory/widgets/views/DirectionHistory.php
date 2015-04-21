<?php
/**
 * @var $this DirectionHistory
 * @var $directions array
 */
?>

<ul class="nav nav-tabs" id="direction-history-nav">
	<li role="presentation" class="active">
		<a href="#direction-history-view" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
			История направлений
		</a>
	</li>
	<li role="presentation" class="">
		<a href="#direction-history-register" role="tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">
			Создать направление
		</a>
	</li>
</ul>
<br>
<div class="tab-content">
	<div class="tab-pane fade active in" id="direction-history-view">
		<div class="row">
		<?php foreach ($directions as $direction): ?>
			<div class="col-xs-12 no-padding">
				<div class="col-xs-2">№ <?= $direction["id"] ?></div>
				<div class="col-xs-4"><?= $direction["registration_time"] ?></div>
				<div class="col-xs-6"><?= $direction["analysis_type_short_name"] ?></div>
			</div>
		<?php endforeach ?>
		</div>
	</div>
	<div class="tab-pane fade" id="direction-history-register">
		<?php $this->widget("DirectionCreator", [
			"controls" => [
				"direction-creator-cancel" => [
					"label" => "Отменить",
					"class" => "btn btn-default",
					"type" => "button",
					"style" => "margin-left: 10px"
				],
			],
			"defaults" => [
				"medcard_id" => $this->medcard
			]
		]) ?>
	</div>
</div>