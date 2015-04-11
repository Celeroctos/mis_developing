<?php
/**
 * @var TreatmentController $this - Self instance
 * @var int $directionRepeats - Count of direction repeats
 */

$this->widget("Modal", [
	"title" => "Создать направление",
	"body" => $this->getWidget("AutoForm", [
		"model" => new LDirectionForm(),
		"id" => "direction-register-form",
		"url" => Yii::app()->getBaseUrl() . "/laboratory/direction/register"
	]),
	"id" => "direction-register-modal",
	"buttons" => [
		"register" => [
			"text" => "Создать",
			"class" => "btn btn-primary",
			"type" => "submit"
		]
	]
]);

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
	"title" => "Поиск медкарты в ЛИС",
	"body" => CHtml::tag("div", [
		"style" => "padding: 10px"
	], $this->getWidget("MedcardSearch", [
		"tableWidget" => "MedcardTable"
	])),
	"id" => "lis-medcard-search-modal",
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
	"title" => "Новое направление",
	"body" => CHtml::tag("div", [
		"style" => "padding: 10px"
	], $this->getWidget("DirectionCreator"))
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
			"class" => "btn btn-default",
			"type" => "button",
			"attributes" => [
				"data-toggle" => "modal",
				"data-target" => "#mis-medcard-search-modal"
			],
			"align" => "center"
		],
		"lis-find-button" => [
			"text" => "<span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;Пациент ЛИС",
			"class" => "btn btn-success",
			"type" => "button",
			"attributes" => [
				"data-toggle" => "modal",
				"data-target" => "#lis-medcard-search-modal"
			],
			"align" => "center"
		]
	],
	"class" => "modal-90"
]);

$this->widget("Modal", [
	"title" => "Регистрация направления",
	"body" => $this->getWidget("AutoForm", [
		"model" => new LDirectionForm("treatment.edit")
	]),
	"id" => "direction-register-modal"
]); ?>

<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-4 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded active" data-tab="#treatment-direction-grid-wrapper" type="button">
				<span>Направления</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#treatment-repeated-grid-wrapper" type="button">
				<span>Повторный забор образцов</span>
				<span class="badge">
					<?= $directionRepeats ?>
				</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded" type="button" data-toggle="modal" data-target="#medcard-editable-viewer-modal" aria-expanded="false">
				<span>Создать направление</span>
			</button>
		</div>
	</div>
	<div class="treatment-table-wrapper">
		<div id="treatment-direction-grid-wrapper">
			<?php $this->widget("Panel", [
				"title" => "Направления на анализ",
				"body" => $this->createWidget("DirectionTable", [
					"where" => "status <> 3"
				]),
				"collapsible" => false
			]) ?>
		</div>
		<div id="treatment-repeated-grid-wrapper" class="no-display">
			<?php $this->widget("Panel", [
				"title" => "Направления на анализ",
				"body" => $this->createWidget("DirectionTable", [
					"where" => "status = 3"
				]),
				"collapsible" => false
			]) ?>
		</div>
	</div>
</div>
<br>
<br>