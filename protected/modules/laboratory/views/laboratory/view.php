<?php
/**
 * @var $this LaboratoryController
 * @var $total int
 * @var $ready int
 * @var $analyzers array
 */
$this->widget("Modal", [
	"title" => "Информация о направлении",
	"body" => CHtml::tag("h1", [], "Направление не выбрано"),
	"id" => "treatment-about-direction-modal"
]) ?>
<?php $this->beginWidget("Modal", [
	"title" => "Информация о направлениях",
	"id" => "laboratory-info-modal"
]) ?>
<table class="table table-bordered">
	<thead>
	<tr>
		<td><b>Обозначение</b></td>
		<td><b>Описание</b></td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="danger" width="100"></td>
		<td>Недоступно для этого типа анализаторов</td>
	</tr>
	<tr>
		<td class="warning" width="100"></td>
		<td>Анализ завершен</td>
	</tr>
	<tr>
		<td class="success" width="100"></td>
		<td>Текущее направление</td>
	</tr>
	<tr>
		<td class="default" width="100" align="middle">
			<img src="/images/locked59.png" style="height: 15px; width: 15px;">
		</td>
		<td>Уже отправлено на анализатор</td>
	</tr>
	</tbody>
</table>
<?php $this->endWidget("Modal") ?>
<script type="text/javascript" src="<?= Yii::app()->createUrl("js/laboratory/laboratory.js") ?>"></script>
<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-6 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded treatment-header-wrapper-active" data-tab="#laboratory-direction-grid-wrapper" type="button">
				<span>Направления и Анализаторы</span>
			</button>
		</div>
		<div class="col-xs-6 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#laboratory-ready-grid-wrapper" type="button">
				<span>Готовые результаты</span>
				<span class="badge" id="laboratory-ready-counts"><?= $ready ?></span>
			</button>
		</div>
	</div>
	<div class="laboratory-table-wrapper table-wrapper">
		<div id="laboratory-direction-grid-wrapper" class="col-xs-12 no-padding">
			<?php $this->widget("TabMenu", [
				"style" => TabMenu::STYLE_PILLS,
				"id" => "analyzer-tab-menu",
				"items" => $analyzers,
				"special" => "analyzer-task-menu-item",
				"active" => array_values($analyzers)[0]["data-id"]
			]) ?>
			<hr>
			<div class="row no-margin direction-view-wrapper">
				<div class="col-xs-6 no-padding">
					<?php $this->widget("DirectionPanel", [
						"title" => "Все направления на анализ",
						"body" => $this->createWidget("DirectionTableLaboratory", [
							"controls" => [
								/* "direction-show-icon" => [
									"icon" => "glyphicon glyphicon-list",
									"label" => "Открыть направление"
								], */
								"direction-send-icon" => [
									"icon" => "glyphicon glyphicon-arrow-right",
									"label" => "Отправить на анализатор"
								]
							]
						]),
						"panelClass" => "panel panel-default no-select",
						"status" => LDirection::STATUS_LABORATORY
					]) ?>
				</div>
				<div class="col-xs-6" style="padding-right: 0">
					<?php if ($analyzers["empty"]["disabled"]) { $analyzers = []; } ?>
					<?php foreach ($analyzers as $class => $analyzer): ?>
						<?php $first = !isset($first) ?>
						<div class="col-xs-12 no-padding laboratory-tab-container" id="<?= $analyzer["data-tab"] ?>" style="<?= $first ? "display: block;" : "display: none;" ?>">
							<?php $this->widget("Panel", [
								"title" => $this->getWidget("ControlMenu", [
									"controls" => [
										"analyzer-queue-start-button" => [
											"label" => "Начать",
											"icon" => "glyphicon glyphicon-play",
											"class" => "btn btn-default"
										],
										/* "analyzer-queue-stop-button" => [
											"label" => "Остановить",
											"icon" => "glyphicon glyphicon-stop",
											"class" => "btn btn-default"
									 	], */
									],
									"mode" => ControlMenu::MODE_BUTTON
								]),
								"body" => CHtml::tag("h3", [
										"class" => "text-center"
									], "Пусто") . CHtml::tag("ul", [
										"class" => "nav nav-pills nav-stacked analyzer-queue-container"
									], ""),
								"controls" => [
									"analyzer-queue-clear-button" => [
										"label" => "Очистить",
										"icon" => "glyphicon glyphicon-refresh",
										"class" => "btn btn-warning"
									],
								],
								"controlMode" => ControlMenu::MODE_BUTTON,
								"titleWrapperClass" => "col-xs-6 text-left no-padding",
								"controlsWrapperClass" => "col-xs-6 text-right no-padding",
								"contentClass" => "col-xs-12 no-padding no-margin panel-content",
								"id" => "analyzer-task-viewer",
								"footer" => CHtml::tag("div", [
									"class" => "progress"
								], CHtml::tag("div", [
									"class" => "progress-bar progress-bar-striped active",
									"role" => "progressbar",
									"aria-valuenow" => "0",
									"aria-valuemin" => "0",
									"aria-valuemax" => "100",
									"style" => "width: 0"
								], CHtml::tag("span", [
									"class" => "cr-only"
								], "")))
							]); ?>
							<hr>
							<div class="btn-group">
								<button class="btn btn-default laboratory-analyzer-info-button btn-lg" data-toggle="modal" data-target="#laboratory-info-modal">
									<span class="glyphicon glyphicon-info-sign"></span>
									&nbsp;Помощь
								</button>
								<button class="btn btn-default laboratory-analyzer-info-button btn-lg" data-id="<?= $analyzer["data-id"] ?>">
									<span class="glyphicon glyphicon-list-alt"></span>
									&nbsp;<?= $analyzer["label"] ?>
								</button>
							</div>
							<hr>
							<div class="laboratory-clock-wrapper"></div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<div id="laboratory-ready-grid-wrapper" class="no-display text-left">
			<hr>
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
<span class="glyphicon glyphicon-arrow-right laboratory-tr-pointer" style="display: none;"></span>
<div class="clock-container" style="display: none;">
	<?= CHtml::image("/images/clock-base.png", "", [
		"width" => "100%",
		"height" => "100%"
	]) ?>
	<?= CHtml::image("/images/clock-hand.png", "", [
		"width" => "100%",
		"height" => "100%"
	]) ?>
	<?= CHtml::image("/images/clock-hour-hand.png", "", [
		"width" => "100%",
		"height" => "100%"
	]) ?>
</div>