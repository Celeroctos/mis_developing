<?php
/**
 * @var TreatmentController $this - Self instance
 * @var int $directionRepeats - Count of direction repeats
 */

$this->widget("Modal", [
	"title" => "Поиск медкарты в МИС",
	"body" => CHtml::tag("div", [
		"style" => "padding: 10px"
	], $this->getWidget("MedcardSearch", [
		"tableWidget" => "MedcardTable2"
	])),
	"id" => "mis-medcard-search-modal",
	"buttons" => [
		"load" => [
			"text" => "Открыть",
			"class" => "btn btn-primary",
			"attributes" => [
				"data-loading-text" => "Загрузка ..."
			],
			"type" => "button"
		]
	],
	"class" => "modal-lg"
]);

$this->widget("Modal", [
	"title" => "Медицинская карта № " . CHtml::tag("span", [
			"id" => "card_number"
		], ""),
	"body" => $this->getWidget("MedcardEditableViewer"),
	"id" => "medcard-editable-viewer-modal",
	"buttons" => [
		"save-button" => [
			"text" => "Сохранить",
			"type" => "button",
			"class" => "btn btn-primary"
		],
		"copy-button" => [
			"text" => "Копировать",
			"class" => "btn btn-default",
			"type" => "button",
			"align" => "left"
		],
		"insert-button" => [
			"text" => "Вставить",
			"class" => "btn btn-default",
			"type" => "button",
			"align" => "left"
		],
		"clear-button" => [
			"text" => "Очистить",
			"class" => "btn btn-warning",
			"type" => "button",
			"align" => "left"
		],
		"mis-find-button" => [
			"text" => "<span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;Пациент МИС",
			"class" => "btn btn-success",
			"type" => "button",
			"attributes" => [
				"data-toggle" => "modal",
				"data-target" => "#mis-medcard-search-modal"
			],
			"align" => "center"
		]
	],
	"class" => "modal-90"
]);

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
]); ?>
<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded treatment-header-wrapper-active" data-tab="#treatment-direction-grid-wrapper" type="button">
				<span>Направления</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#treatment-repeated-grid-wrapper" type="button">
				<span>Повторный забор образцов</span>
				<span class="badge" id="treatment-repeat-counts">
					<?= $directionRepeats ?>
				</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button id="header-register-direction-button" class="btn btn-default btn-block treatment-header-rounded" type="button" data-target="#medcard-editable-viewer-modal" aria-expanded="false">
				<span>Создать направление</span>
			</button>
		</div>
	</div>
	<div class="treatment-table-wrapper table-wrapper">
		<hr>
		<div id="treatment-direction-grid-wrapper">
			<?php $this->widget("DirectionPanel", [
				"title" => "Направления на анализ",
				"body" => $this->createWidget("DirectionTableTreatment"),
				"status" => LDirection::STATUS_TREATMENT_ROOM
			]) ?>
		</div>
		<div id="treatment-repeated-grid-wrapper" class="no-display">
			<?php $this->widget("DirectionPanel", [
				"title" => "Направления на повторный забор образца",
				"body" => $this->createWidget("DirectionTableTreatmentRepeat"),
				"status" => LDirection::STATUS_TREATMENT_REPEAT
			]) ?>
		</div>
	</div>
	<hr>
	<?php $this->widget("Panel", [
		"title" => "Медицинские карты лаборатории",
		"body" => $this->createWidget("TreatmentMedcardSearch"),
		"id" => "treatment-laboratory-medcard-table-panel",
		"collapsible" => false
	]) ?>
</div>
<script>
$(document).ready(function() {
	$(document).on("barcode.captured", function(e, p) {
		Laboratory_DirectionTable_Widget.show(p.barcode);
	});
});
</script>