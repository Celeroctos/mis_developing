<?php

class Table extends Widget {

	/**
	 * @var string - Default order table column name
	 */
	public $sort;

	/**
	 * @var bool - Order direction
	 */
	public $desc = false;

	/**
	 * @var int - Maximum displayed rows per page
	 */
	public $limit = 10;

	/**
	 * @var int - Current displayed page
	 */
	public $page = 1;

	/**
	 * @var CDbCriteria|string - Search criteria
	 */
	public $criteria = null;

	/**
	 * @var string - CDbCriteria condition
	 */
	public $condition = null;

	/**
	 * @var array - CDbCriteria parameters
	 */
	public $params = null;

	/**
	 * @var TableProvider - Table provider adapter for
	 * 	your table, which has all information about
	 * 	queries and order rules
	 */
	public $provider = null;

	/**
	 * @var ActiveRecord|string - Table's active record instance
	 * @deprecated
	 */
	public $table = null;

	public $header = null;

	/**
	 * @var null
	 * @deprecated
	 */
    public $pk = null;

	public $hideArrow = false;
	public $controls = [];

	/**
	 * @var null
	 * @deprecated
	 */
	public $pages = null;

	/**
	 * @var string
	 * @deprecated
	 */
	public $conditions = "";

	/**
	 * @var array
	 * @deprecated
	 */
	public $parameters = [];

	public $disablePagination = false;
	public $click = null;

	/**
	 * @var bool - Should table be empty after first page load, set
	 * 	it to true if your table contains big amount of rows and
	 * 	it's initial render will slow down all render processes
	 */
	public $empty = false;

	/**
	 * Run widget and return just rendered content
	 * @return string - Just rendered content
	 * @throws CException
	 */
	public function run() {

		// Check table instance
		if (is_string($this->table)) {
			$this->table = new $this->table();
		}

		// Set default order key
		if (empty($this->sort)) {
			$this->sort = "id";
		}

		if (is_string($this->params)) {
			$this->params = unserialize(urldecode($this->params));
		}
		if (!is_object($this->criteria)) {
			$this->criteria = new CDbCriteria();
		}

		if (is_string($this->condition) && is_array($this->parameters)) {
			$this->criteria->condition = $this->condition;
			$this->criteria->params = $this->params;
		}

		if ($this->provider == null && $this->table instanceof ActiveRecord) {
			$this->provider = $this->table->getDefaultTableProvider();
		}

		// Get total rows
		if ($this->empty == false) {
			if ($this->provider == null) {
				$total = $this->table->getTableCount($this->criteria);
				$command = $this->table->getTable()->order(
					$this->sort.($this->desc ? " desc" : "")
				)->andWhere($this->condition, $this->parameters);
				if ($this->criteria) {
					$command->andWhere($this->criteria->condition, $this->criteria->params);
				}
				$this->pages = intval($total / $this->limit + ($total / $this->limit * $this->limit != $total ? 1 : 0));
				if (!$this->pages) {
					$this->pages = 1;
				}
				$command->limit($this->limit);
				$command->offset($this->limit * ($this->page - 1));
				$data = $command->queryAll();
			} else {
				$this->provider->getPagination()->currentPage = $this->page;
				if ($this->condition != null) {
					$this->provider->getCriteria()->addCondition($this->condition);
				}
				if ($this->params != null) {
					$this->provider->getCriteria()->params += $this->params;
				}
				if (is_object($this->criteria)) {
					$this->provider->getCriteria()->mergeWith($this->criteria);
				}
				if (!empty($this->sort)) {
					$this->provider->orderBy = $this->sort . ($this->desc ? " desc" : "");
				}
				$data = $this->provider->fetchData();
			}
		} else {
			$this->disablePagination = true;
			$data = [];
		}

		// Prevent array offset errors
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

		// Set default primary key
        if (!$this->pk) {
            $this->pk = "id";
        }

		// Render widget (absolute path for sub-modules widgets, which extends current)
		return $this->render("application.widgets.views.Table", [
			"data" => $data
		]);
	}

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

	public function renderFooter() {
		print CHtml::openTag("tfoot");
		print CHtml::openTag("tr");
		print CHtml::openTag("td", [
			"colspan" => count($this->header) + 1
		]);
		if (!$this->disablePagination) {
			$this->widget("Pagination", [
				"limit" => $this->provider->getPagination()->pageLimit,
				"action" => "Table.page.call",
				"page" => $this->provider->getPagination()->currentPage,
				"pages" => $this->provider->getPagination()->totalPages
			]);
		}
		print CHtml::closeTag("td");
		print CHtml::closeTag("tr");
		print CHtml::closeTag("tfoot");
		if ($this->disablePagination) {
			return ;
		}
	}
}