<?php
/**
 * @var $this Laboratory_Widget_DirectionHistory
 * @var $directions array
 */
$register = UniqueGenerator::generate("direction-history-register");
$view = UniqueGenerator::generate("direction-history-view");
?>

<div class="direction-history-wrapper">
	<ul class="nav nav-tabs nav-justified">
		<li role="presentation" class="active">
			<a href="#<?= $view ?>" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
				<span class="glyphicon glyphicon-list"></span> История
			</a>
		</li>
		<li role="presentation" class="">
			<a href="#<?= $register ?>" role="tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">
				<span class="glyphicon glyphicon-plus"></span> Новое
			</a>
		</li>
	</ul>
	<br>
	<div class="tab-content">
		<div class="tab-pane fade active in" id="<?= $view ?>">
			<div class="row">
				<?php foreach ($directions as $direction): ?>
					<div class="col-xs-12 no-padding">
						<div class="col-xs-2">№ <?= $direction["id"] ?></div>
						<div class="col-xs-4"><?= $direction["sending_date"] ?></div>
						<div class="col-xs-6"><?= $direction["analysis_type_short_name"] ?></div>
					</div>
				<?php endforeach ?>
				<?php if (empty($directions)): ?>
					<h3 class="text-center no-margin no-padding">Нет направлений</h3>
				<?php endif ?>
			</div>
		</div>
		<div class="tab-pane fade" id="<?= $register ?>">
			<?php $this->widget("Laboratory_Widget_DirectionCreator", [
				"defaults" => [
					"medcard_id" => $this->medcard
				]
			]) ?>
		</div>
	</div>
</div>