<?php

/**
 * Class GridProvider
 *
 * @property $menu ControlMenu
 */
abstract class GridProvider extends ActiveDataProvider {

	/**
	 * Default control menu display mode. To override it change
	 * control menu provider's field [mode]
	 *
	 * @see ControlMenu::mode
	 * @see menu
	 */
	const CONTROL_MENU_MODE = ControlMenu::MODE_ICON;

	/**
	 * Default control menu special class for menu items, it adds
	 * automatically to every menu item
	 *
	 * @see ControlMenu::special
	 * @see menu
	 */
	const CONTROL_MENU_SPECIAL = "table-control-icon";

	/**
	 * @var string table's identification number
	 */
	public $id = null;

	/**
	 * @var array with name of columns identifications
	 * 	which should be displayed
	 */
	public $columns = null;

	/**
	 * @var string name of model's primary key which
	 * 	copies to every <tr> element
	 */
	public $primaryKey = "id";

	/**
	 * @var array with list of available page
	 * 	limits
	 */
	public $limits = [
		10, 25, 50, 75
	];

	/**
	 * @var string control menu elements alignment
	 */
	public $menuAlignment = null;

	/**
	 * @var bool should table be empty after first page load, set
	 * 	it to true if your table contains big amount of rows and
	 * 	it's initial render will slow down all render processes, also
	 * 	it removes table footer, cuz it should contains search parameters
	 *
	 * @see renderFooter
	 */
	public $emptyData = false;

	/**
	 * @var bool should widget renders table header with
	 * 	information columns names and order directions
	 */
	public $hasHeader = true;

	/**
	 * @var array|false with configuration for widget, that
	 * 	renders
	 */
	public $footer = [
		"withPagination" => true,
		"withLimit" => true,
		"withSearch" => false,
	];

	/**
	 * @var bool should widget renders table footer with
	 * 	extra table control elements and count information
	 */
	public $hasFooter = true;

	/**
	 * @var string default table class which wraps table's
	 * 	header, body and footer
	 */
	public $tableClass = "table table-striped core-table";

	/**
	 * @var string text message for received empty array
	 * 	with data
	 */
	public $textNoData = "Нет данных";

	/**
	 * @var string message will be displayed if
	 * 	field [emptyData] set to true
	 *
	 * @see emptyData
	 */
	public $textEmptyData = "Не выбраны критерии поиска";

	/**
	 * @var string default placement for bootstrap tooltip
	 * 	component [left, right, top, bottom]
	 */
	public $tooltipDefaultPlacement = "left";

	/**
	 * @var string width of column with control elements
	 */
	public $menuWidth = "50px";

	/**
	 * @var string identification string of current
	 * 	module, only for internal usage
	 *
	 * @internal changing that property may
	 * 	crash widget or do nothing
	 */
	public $module = null;

	/**
	 * @var string with name of glyphicon class for
	 * 	chevron order icon (DESC)
	 */
	public $chevronUpClass = "glyphicon glyphicon-chevron-up";

	/**
	 * @var string with name of glyphicon class for
	 * 	chevron order icon (ASC)
	 */
	public $chevronDownClass = "glyphicon glyphicon-chevron-down";

	/**
	 * @var string with javascript code which provides actions on
	 * 	row's click, you should set only function name, other method
	 *	call binds automatically
	 */
	public $clickEvent = null;

	/**
	 * @var string|callable with javascript code which provides
	 * 	actions on row's double click
	 */
	public $doubleClickEvent = null;

	/**
	 * @var array with extra attributes, which should be
	 * 	added to <table> tag
	 */
	public $extra = [];

	/**
	 * @return ControlMenu instance of widget class, which
	 * 	should renders control menu for current provider
	 */
	public function getMenu() {
		if ($this->_menu == null || is_array($this->_menu)) {
			return $this->setMenu($this->menu);
		} else {
			return $this->_menu;
		}
	}

	public function setMenu($menu) {
		if (!$menu instanceof CWidget && is_array($menu)) {
			return $this->_menu = Yii::app()->getController()->createWidget("ControlMenu", $menu);
		} else {
			return $this->_menu = $menu;
		}
	}

	protected function fetchData() {
		if (!$this->emptyData) {
			return parent::fetchData();
		} else {
			return [];
		}
	}

	private $_menu = null;
}