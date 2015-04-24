<?php
/**
 * @var $this TreatmentController
 */
$this->widget("Modal", [
	"title" => "Окошко",
	"body" => "Окошко",
	"id" => "test-modal"
]);
?>

<h2>Панели</h2>
<h5>Самый примитивный тип панели</h5>
<hr>
<?php $this->beginWidget("Panel", [
	"title" => "Simple Panel Title"
]); ?>
<p>Simple Panel Body</p>
<?php $this->endWidget("Panel"); ?>
<?php $this->beginWidget("Panel", [
	"title" => "Simple Panel Title",
	"panelClass" => Panel::PANEL_CLASS_DANGER
]); ?>
<p>Simple Panel Body</p>
<?php $this->endWidget("Panel"); ?>
<hr><br>

<h2>Сворачиваемые панели</h2>
<h5>Можно просто установить [collapsible => true]</h5>
<hr>
<?php $this->beginWidget("Panel", [
	"title" => "Simple Panel Title",
	"collapsible" => true
]); ?>
<p>Simple Panel Body</p>
<?php $this->endWidget("Panel"); ?>
<?php $this->beginWidget("Panel", [
	"title" => "Simple Panel Title",
	"panelClass" => Panel::PANEL_CLASS_DANGER,
	"collapsible" => true
]); ?>
<p>Simple Panel Body</p>
<?php $this->endWidget("Panel"); ?>
<hr><br>

<h2>Обновляемые панели</h2>
<h5>Панели, которые имеют встроенный компонент, получают встроенную систему автоматических обновлений. Компонент встраивается в панель через поле [body], которое должно являться объектом класса [Widget], которое было создано через [Widget::createWidget] метод или любой другой со схожим функционалом</h5>
<hr>
<?php $this->widget("Panel", [
	"title" => "Simple Panel Title",
	"body" => $this->createWidget("TreatmentMedcardSearch")
]); ?>
<hr><br>

<h2>Управляемые панели</h2>
<h5>Можно расширять элементы управления панелью и менять режим отображения панели, по умолчанию отображаются иконки. Если размера строки не хватает для размещения всех элементов, то можно просто расширить классы [titleWrapperClass] и [controlsWrapperClass]</h5>
<hr>
<?php $this->widget("Panel", [
	"title" => "Simple Panel Title",
	"controls" => [
		"create-control-icon" => [
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить",
			"style" => "color: darkred"
		],
	],
	"panelClass" => Panel::PANEL_CLASS_PRIMARY,
	"collapsible" => true
]); ?>
<?php $this->widget("Panel", [
	"title" => "Simple Panel Title",
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"titleWrapperClass" => "col-xs-6 text-left no-padding",
	"controlsWrapperClass" => "col-xs-6 text-right no-padding",
	"panelClass" => Panel::PANEL_CLASS_DANGER,
	"controlMode" => ControlMenu::MODE_BUTTON,
	"collapsible" => true,
]); ?>
<?php $this->widget("Panel", [
	"title" => "Simple Panel Title",
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"titleWrapperClass" => "col-xs-11 text-left no-padding",
	"controlsWrapperClass" => "col-xs-1 text-right no-padding",
	"panelClass" => Panel::PANEL_CLASS_PRIMARY,
	"controlMode" => ControlMenu::MODE_MENU,
	"collapsible" => true,
]); ?>
<?php $this->widget("Panel", [
	"title" => "Simple Panel Title",
	"controls" => [
		"xs-control-icon" => [
			"class" => "btn btn-default btn-xs",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Очень маленькая кнопка"
		],
		"sm-control-icon" => [
			"class" => "btn btn-default btn-sm",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Маленькая кпнока"
		],
		"control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Кнопка"
		],
		"lg-control-icon" => [
			"class" => "btn btn-default btn-lg",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Большая кнопка"
		],
		"modal-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Открыть окошко",
			"data-toggle" => "modal",
			"data-target" => "#test-modal"
		],
	],
	"titleWrapperClass" => "col-xs-2 text-left no-padding",
	"controlsWrapperClass" => "col-xs-10 text-right no-padding",
	"panelClass" => Panel::PANEL_CLASS_DEFAULT,
	"controlMode" => ControlMenu::MODE_BUTTON,
	"collapsible" => true,
]); ?>
<hr><br>

<h2>Элементы управления</h2>
<h5>Можно использовать элементы управления в своих компонентах с зраличными режимами отображения</h5>
<hr>
<?php $this->widget("ControlMenu", [
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"mode" => ControlMenu::MODE_ICON
]); ?>
<hr>
<?php $this->widget("ControlMenu", [
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"mode" => ControlMenu::MODE_BUTTON
]); ?>
<hr>
<?php $this->widget("ControlMenu", [
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"mode" => ControlMenu::MODE_TEXT
]); ?>
<hr>
<div style="width: 25px">
<?php $this->widget("ControlMenu", [
	"controls" => [
		"create-control-icon" => [
			"class" => "btn btn-success",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Создать"
		],
		"edit-control-icon" => [
			"class" => "btn btn-default",
			"icon" => "glyphicon glyphicon-pencil",
			"label" => "Изменить"
		],
		"remove-control-icon" => [
			"class" => "btn btn-danger",
			"icon" => "glyphicon glyphicon-remove",
			"label" => "Удалить"
		],
	],
	"mode" => ControlMenu::MODE_MENU
]); ?>
</div>
<hr><br>
<span class="glyphicon glyphicon-option-vertical"></span>