<?php

class Table extends Widget {

	/**
	 * @var CDbCriteria|string - Default table provider
	 * 	search criteria, you can use it for search
	 * @see MedcardController::actionSearch - Usage example
	 */
	public $criteria = null;

	/**
	 * @var string - CDbCriteria condition, is used for
	 * 	table order or search
	 * @internal
	 */
	public $condition = null;

	/**
	 * @var array - CDbCriteria parameters, is used for
	 * 	table order or search
	 * @internal
	 */
	public $params = null;

	/**
	 * @var TableProvider - Table provider adapter for
	 * 	your table, which has all information about
	 * 	queries and order rules
	 */
	public $provider = null;

	/**
	 * @var array - An array with columns configuration, it
	 * 	has the same structure as in FormModel configuration
	 *
	 * + label - Column label to display
	 * + [style] - Style for current column, like width or color
	 * + [type] - Column default type (for filters in future)
	 * + [width] - Default column width
	 *
	 * @see FormModel::config
	 */
	public $header = null;

	/**
	 * @var string - Default primary key for current table, it
	 * 	uses to receive from data rows unique identification number
	 * 	for tr's [data-id] field
	 */
    public $primaryKey = "id";

	/**
	 * @var int - Current page, that should be displayed, it uses
	 * 	with TablePagination class
	 */
	public $currentPage = 1;

	/**
	 * @var null|string - Default order by key, for null value
	 * 	primary key is used
	 */
	public $orderBy = null;

	/**
	 * @var bool - Shall hide order arrows for tables, which doesn't
	 * 	support ordering or its redundant
	 */
	public $hideOrderByIcon = false;

	/**
	 * @var array - Array with elements controls buttons, like edit
	 * 	or remove. Array's key is class for [a] tag and value is
	 * 	class for [span] tag like glyphicon or button
	 * @see renderControls
	 */
	public $controls = [];

	/**
	 * @var string - String with search conditions, uses for
	 * 	table order and search (simply more suitable method
	 * 	for getWidget action)
	 * @see LController::actionGetWidget
	 * @internal
	 */
	public $conditions = "";

	/**
	 * @var array - Array with parameters for search conditions, uses for
	 * 	table order and search (simply more suitable method for getWidget action)
	 * @see LController::actionGetWidget
	 * @internal
	 */
	public $parameters = [];

	/**
	 * @var string - Custom onClick action for table row, you have to
	 * 	set only function or method name without any arguments, cuz it
	 * 	converts it to next format [{$click}.call(this, id)], where
	 * 	id is primary key value of your row from database
	 * @see primaryKey
	 */
	public $click = null;

	/**
	 * @var bool - Should table be empty after first page load, set
	 * 	it to true if your table contains big amount of rows and
	 * 	it's initial render will slow down all render processes, also
	 * 	it removes table footer, cuz it should contains search parameters
	 * @see renderFooter
	 */
	public $emptyData = false;

	/**
	 * @var string - Default table class
	 */
	public $tableClass = "table table-striped table-bordered";

	/**
	 * @var array - Array with data to display, it can be used only
	 * 	if table provider null
	 */
	public $data = null;

	/**
	 * @var string - Unique identification value of current
	 * 	table, by default it generates automatically with prefix
	 * @see UniqueGenerator::generate
	 */
	public $id = null;

	/**
	 * @var int - How many rows should be displayed
	 * 	per one page, default is table pagination's limit
	 * @see TablePagination::pageLimit
	 */
	public $pageLimit = null;

	/**
	 * @var array - An array with available table limits with
	 * 	count of displayable rows per one page, set that value
	 * 	to false to disable limits
	 * @see renderFooter
	 */
	public $availableLimits = [
		10, 25, 50, 75
	];

	/**
	 * @var string - Text message for received empty array
	 * 	with data
	 */
	public $textNoData = "Нет данных";

	/**
	 * @var string - That message will be displayed if
	 * 	field [emptyData] set to true
	 * @see emptyData
	 */
	public $textEmptyData = "Не выбраны критерии поиска";

	/**
	 * Run widget and return just rendered content
	 * @return string - Just rendered content
	 * @throws CException
	 */
	public function run() {
		if (!$this->provider instanceof TableProvider && is_array($this->data)) {
			throw new CException("Table provider must be an instance of TableProvider and don't have to be null");
		}
		if (is_string($this->params)) {
			$this->params = unserialize(urldecode($this->params));
		}
		if (empty($this->criteria)) {
			$this->criteria = new CDbCriteria();
		}
		if (is_string($this->condition) && !empty($this->condition) && is_array($this->parameters)) {
			$this->criteria->condition = $this->condition;
			$this->criteria->params = $this->params;
		}
		if (empty($this->id)) {
			$this->id = UniqueGenerator::generate("table");
		}
		foreach ($this->header as $key => &$value) {
			if (!isset($value["id"])) {
				$value["id"] = "";
			}
			if (!isset($value["class"])) {
				$value["class"] = "";
			}
			if (!isset($value["style"])) {
				$value["style"] = "";
			}
		}
		if (empty($this->orderBy)) {
			$this->orderBy = $this->primaryKey;
		}
		return $this->render("application.widgets.views.Table");
	}

	/**
	 * Fetch array with data from table provider or something else
	 * @return array - Array with provider's data
	 */
	public function fetchData() {
		if ($this->emptyData !== false) {
			if ($this->provider !== null) {
				$this->provider->getPagination()->calculate(0);
			}
			return [];
		} else if ($this->provider == null && is_array($this->data)) {
			return $this->data;
		}
		$this->provider->getPagination()->currentPage = $this->currentPage;
		if ($this->pageLimit != null) {
			$this->provider->getPagination()->pageLimit = $this->pageLimit;
		}
		if ($this->condition != null) {
			$this->provider->getCriteria()->addCondition($this->condition);
		}
		if ($this->params != null) {
			$this->provider->getCriteria()->params += $this->params;
		}
		if (is_object($this->criteria)) {
			$this->provider->getCriteria()->mergeWith($this->criteria);
		}
		if (!empty($this->orderBy)) {
			$this->provider->orderBy = $this->orderBy;
		}
		return $this->data = $this->provider->fetchData();
	}

	/**
	 * Render extra information for table header with search
	 * conditions and parameters, need for update requests
	 */
	public function renderExtra() {
		$options = [
			"data-class" => get_class($this),
			"data-url" => $this->createUrl()
		];
		if (!empty($this->criteria->condition)) {
			$options["data-condition"] = $this->criteria->condition;
		}
		if (!empty($this->provider->getPagination()->pageLimit)) {
			if ($this->pageLimit !== null) {
				$options["data-limit"] = $this->pageLimit;
			} else {
				$options["data-limit"] = $this->provider->getPagination()->pageLimit;
			}
		}
		if (!empty($this->criteria->params)) {
			$options["data-parameters"] = urlencode(serialize($this->criteria->params));
		}
		print CHtml::renderAttributes($options);
	}

	/**
	 * Render table's body
	 */
	public function renderBody() {
		foreach ($this->fetchData() as $key => $value) {
			$options = [
				"data-id" => $value[$this->primaryKey],
				"class" => "core-table-row"
			];
			if (is_string($this->click)) {
				$options["onclick"] = $this->click . "(this, '{$value[$this->primaryKey]}')";
			}
			print CHtml::openTag("tr", $options);
			foreach ($this->header as $k => $v) {
				print CHtml::tag("td", [
					"align" => "left",
					"class" => "core-table-cell"
				], isset($value[$k]) ? $value[$k] : "");
			}
			$this->renderControls();
			print CHtml::closeTag("tr");
		}
		if (count($this->data) == 0) {
			if ($this->emptyData) {
				$text = $this->textEmptyData;
			} else {
				$text = $this->textNoData;
			}
			print CHtml::tag("tr", [], CHtml::tag("td", [
				"colspan" => count($this->header) + 1
			], "<b>$text</b>"));
		}
	}

	/**
	 * Render table header with information about
	 * columns
	 */
	public function renderHeader() {
		print CHtml::openTag("tr", [
			"class" => "core-table-row"
		]);
		foreach ($this->header as $key => $value) {
			$options = [
				"data-key" => $key,
				"onclick" => "$(this).table('order', '$key')",
				"align" => "left"
			];
			if (!empty($value["id"])) {
				$options["id"] = $value["id"];
			}
			if (!empty($value["class"])) {
				$options["class"] = $value["class"];
			}
			if (!empty($value["style"])) {
				$options["style"] = $value["style"];
			}
			print CHtml::openTag("td", $options);
			print CHtml::tag("b", [], $this->header[$key]["label"]);
			$this->renderChevron($key);
			print CHtml::closeTag("td");
		}
		if (count($this->controls) > 0) {
			print CHtml::tag("td", [
				"align" => "middle",
				"style" => "width: 50px"
			]);
		}
		print CHtml::closeTag("tr");
	}

	/**
	 * Render chevron only for ordered column
	 * @param string $key - Current key
	 */
	public function renderChevron($key) {
		if (($p = strpos($this->orderBy, " ")) !== false) {
			$orderBy = substr($this->orderBy, 0, $p);
		} else {
			$orderBy = $this->orderBy;
		}
		if ($orderBy !== $key || $this->hideOrderByIcon !== false) {
			return ;
		}
		if (strpos($this->orderBy, "desc") !== false) {
			$class = "glyphicon glyphicon-chevron-up table-order table-order-desc";
		} else {
			$class = "glyphicon glyphicon-chevron-down table-order table-order-asc";
		}
		print CHtml::tag("span", [
			"class" => $class
		]);
	}

	/**
	 * Render table controls for each row
	 */
	public function renderControls() {
		if (!count($this->controls)) {
			return ;
		}
		print CHtml::openTag("td", [
			"align" => "middle"
		]);
		foreach ($this->controls as $c => $class) {
			print CHtml::tag("a", [
				"href" => "javascript:void(0)",
				"class" => $c
			], CHtml::tag("span", [
				"class" => $class
			]));
		}
		print CHtml::closeTag("td");
	}

	/**
	 * Render table footer with different
	 * control elements, like pagination
	 * or search
	 */
	public function renderFooter() {
		if ($this->emptyData !== false) {
			return ;
		}
		print CHtml::openTag("tr", [
			"class" => "core-table-row"
		]);
		print CHtml::openTag("td", [
			"colspan" => count($this->header) - 1,
			"align" => "left"
		]);
		if ($this->provider->pagination !== false) {
			$this->widget("Pagination", [
				"tablePagination" => $this->provider->getPagination(),
				"clickAction" => function($page) {
					return "$(this).table('page', {$page})";
				}
			]);
		}
		print CHtml::closeTag("td");
		print CHtml::openTag("td", [
			"align" => "right"
		]);
		if ($this->availableLimits !== false) {
			$list = [];
			foreach ($this->availableLimits as $value) {
				$list[$value] = $value;
			}
			if ($this->provider->pagination != null) {
				$limit = $this->provider->pagination->pageLimit;
			} else {
				$limit = $this->pageLimit;
			}
			if ($limit !== null && !isset($list[$limit])) {
				$list[$limit] = $limit;
			} else if ($limit === null) {
				$limit = "";
			}
			print CHtml::dropDownList("availableLimits", $limit, $list, [
				"class" => "form-control text-center",
				"style" => "width: 75px",
				"onchange" => "$(this).table('limit', $(this).val())"
			]);
		}
		print CHtml::closeTag("td");
		print CHtml::closeTag("tr");
	}
}