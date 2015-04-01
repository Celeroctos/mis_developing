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
		"mode" => "mis"
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
		"mode" => "lis"
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
		]
	],
	"class" => "modal-lg"
]);

$this->widget("Modal", [
	"title" => "Новое направление",
	"body" => $this->getWidget("AutoForm", [
		"model" => new LDirectionForm()
	]),
	"id" => "direction-register-modal"
]); ?>

<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="treatment-header-rounded">
			<div class="row col-xs-12">
				<span class="col-xs-10">
					<b>Процедурный кабинет</b><br>
					<span><?= Yii::app()->user->getState("fio") ?></span>
				</span>
				<button class="btn btn-default col-xs-2 logout-button">Выйти</button>
			</div>
		</div>
		<div class="col-xs-4 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded active" data-tab="#treatment-direction-grid-wrapper" type="button">
				<span>Направления</span>
			</button>
		</div>
		<div class="col-xs-4 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#treatment-repeated-grid-wrapper" type="button">
				<span>Повторный забор образцов</span>
				<?php if ($directionRepeats > 0): ?>
					<span class="badge">
						<?= $directionRepeats ?>
					</span>
				<?php endif ?>
			</button>
		</div>
		<div class="col-xs-4 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded" type="button" data-toggle="modal" data-target="#medcard-editable-viewer-modal" aria-expanded="false">
				<span>Создать направление</span>
			</button>
		</div>
	</div>
	<div class="treatment-table-wrapper treatment-header-rounded">
		<div id="treatment-direction-grid-wrapper">
			<?= $this->getWidget("Table", [
				"table" => new LDirection("grid.direction"),
				"header" => [
					"id" => [
						"label" => "#",
						"style" => "width: 15%"
					],
					"card_number" => [
						"label" => "Номер карты",
						"style" => "width: 25%"
					],
					"status" => [
						"label" => "Статус",
						"style" => "width: 20%"
					],
					"sender_id" => [
						"label" => "Направитель",
						"style" => "width: 15%"
					],
					"analysis_type_id" => [
						"label" => "Тип анализа"
					]
				],
				"pk" => "id",
				"sort" => "id",
				"id" => "direction-table",
				"limit" => 25
			]) ?>
		</div>
		<div id="treatment-repeated-grid-wrapper" class="no-display">
            <?= $this->getWidget("Table", [
                "table" => new LDirection("grid.direction"),
                "criteria" => DbCriteria::createWithWhere("is_repeated = 1"),
                "header" => [
                    "id" => [
                        "label" => "#",
                        "style" => "width: 15%"
                    ],
                    "card_number" => [
                        "label" => "Номер карты",
                        "style" => "width: 25%"
                    ],
                    "status" => [
                        "label" => "Статус",
                        "style" => "width: 20%"
                    ],
                    "sender_id" => [
                        "label" => "Направитель",
                        "style" => "width: 15%"
                    ],
                    "analysis_type_id" => [
                        "label" => "Тип анализа"
                    ]
                ],
                "pk" => "id",
                "sort" => "id",
                "id" => "direction-table",
                "limit" => 25
            ]) ?>
		</div>
	</div>
</div>