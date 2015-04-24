<?php
/**
 * @var $this TreatmentController
 */
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
<h5>Панели, которые имеют встроенный компонент и встроенную систему автоматических обновлений. Компонент встраивается в панель через поле [body], которое должно являться объектом класса [Widget], которое было создано через [Widget::createWidget] метод или любой другой со схожим функционалом</h5>
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
			"label" => "Удалить",
			"style" => "color: darkred"
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
			"class" => "btn btn-default btn-lg",
			"icon" => "glyphicon glyphicon-plus",
			"label" => "Большая кнопка"
		],
	],
	"panelClass" => Panel::PANEL_CLASS_DEFAULT,
	"controlMode" => ControlMenu::MODE_BUTTON,
	"collapsible" => true,
]); ?>
<hr><br>