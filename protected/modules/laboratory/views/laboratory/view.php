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
	"buttons" => [
		"open-medcard-button" => [
			"text" => "Открыть медкарту",
			"class" => "btn btn-success",
			"align" => "left"
		]
	],
	"id" => "treatment-about-direction-modal"
]) ?>
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
			]) ?>
			<hr>
			<?php foreach ($analyzers as $class => $analyzer): ?>
				<?php if ($class == "list"): ?>
			<div class="col-xs-12 no-padding laboratory-tab-container" id="<?= $analyzer["data-tab"] ?>">
				<?php $this->widget("DirectionPanel", [
					"title" => "Все направления на анализ",
					"body" => $this->createWidget("DirectionTableLaboratory", [
						"controls" => [
							"direction-show-icon" => [
								"icon" => "glyphicon glyphicon-list",
								"label" => "Открыть направление"
							],
						]
					]),
					"status" => LDirection::STATUS_LABORATORY
				]) ?>
			</div>
				<?php else: ?>
			<div class="col-xs-12 no-padding laboratory-tab-container" id="<?= $analyzer["data-tab"] ?>" style="display: none;">
				<div class="col-xs-6">
					<?php $this->widget("DirectionPanel", [
						"title" => "Направления для (".CHtml::tag("b", [
								"style" => "font-size: 14px"
							], $analyzer["label"]).")",
						"body" => $this->createWidget("DirectionTableLaboratory", [
							"analyzerType" => $analyzer["data-type"]
						]),
						"status" => LDirection::STATUS_LABORATORY
					]) ?>
				</div>
				<div class="col-xs-6">
					<?php $this->widget("Panel", [
						"title" => $this->getWidget("ControlMenu", [
							"controls" => [
								"analyzer-queue-start-button" => [
									"label" => "Начать",
									"icon" => "glyphicon glyphicon-play",
									"class" => "btn btn-default"
								],
								"analyzer-queue-stop-button" => [
									"label" => "Остановить",
									"icon" => "glyphicon glyphicon-stop",
									"class" => "btn btn-default"
								],
							],
							"mode" => ControlMenu::MODE_BUTTON
						]),
						"body" => CHtml::tag("h3", [
							"class" => "text-center"
						], "Пусто") . CHtml::tag("table", [
								"class" => "table table-striped table-bordered analyzer-queue-container"
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
					]); ?>
				</div>
			</div>
				<?php endif ?>
			<?php endforeach ?>
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