<?php
/**
 * @var $this LaboratoryController
 * @var $ready int
 */
?>
<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-6 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded treatment-header-wrapper-active" data-tab="#laboratory-direction-grid-wrapper" type="button">
				<span>Анализатор</span>
			</button>
		</div>
		<div class="col-xs-6 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#laboratory-ready-grid-wrapper" type="button">
				<span>Готовые результаты</span>
				<span class="badge" id="laboratory-ready-counts"><?= $ready ?></span>
			</button>
		</div>
	</div>
	<div class="laboratory-table-wrapper">
		<hr>
		<div id="laboratory-direction-grid-wrapper" class="col-xs-12 no-padding">
			<div class="col-xs-6 no-padding">
				<?php $this->widget("DirectionPanel", [
					"title" => "Направления на анализ",
					"body" => $this->createWidget("DirectionTableLaboratory"),
					"status" => LDirection::STATUS_LABORATORY
				]) ?>
			</div>
			<div class="col-xs-6 no-padding">
				<?php $this->widget("Panel", [
					"title" => "Анализаторы",
					"body" => $this->createWidget("AnalyzerTaskViewer"),
					"controls" => [],
//					"controlMode" => ControlMenu::MODE_MENU,
//					"controlMenuTrigger" => "Анализаторы",
					"id" => "analyzer-task-viewer",
				]); ?>
			</div>
		</div>
		<div id="laboratory-ready-grid-wrapper" class="no-display text-left">
			<?php $this->widget("DirectionPanel", [
				"title" => "Завершенные направления",
				"body" => $this->createWidget("DirectionTableReady"),
				"status" => LDirection::STATUS_READY
			]) ?>
			<hr>
			<button class="btn btn-primary btn-lg">
				<span class="glyphicon glyphicon-print"></span>&nbsp;Печать
			</button>
		</div>
	</div>
</div>
<br>
<input type="text" class="form-control" title="">
<button type="button" title="Поиск направления" class="btn btn-default" data-container="body" data-trigger="click" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<?= htmlspecialchars($this->getWidget("DirectionSearch")) ?>">
	Popover on top
</button>