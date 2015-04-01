<?php

class Table extends Widget {

	use TableTrait;

    /**
     * @var Widget - Sub-widget component with TableTrait element, which
     *  extends Table widget, for example - MedcardTable
	 * @see MedcardTable
     */
	public $widget = null;

	public $table = null;
	public $header = null;
    public $pk = null;
	public $hideArrow = false;
	public $controls = [];
	public $pages = null;
	public $conditions = "";
	public $parameters = [];
	public $disablePagination = false;
	public $click = null;

	/**
	 * @var bool - Should table be empty after first page load, set
	 * 	it to true if your table contains big amount of rows and
	 * 	it's initial render will be slow all render processes
	 */
	public $empty = false;

	/**
	 * Run widget and return just rendered content
	 * @return string - Just rendered content
	 * @throws CException
	 */
	public function run() {

		// Check table instance
		if (!($this->table instanceof ActiveRecord)) {
			throw new CException("Table's model must extends ActiveRecord");
		}

		// Copy parameters from parent widget
		if ($this->widget) {
			foreach ($this->widget as $key => $value) {
				if (!empty($value) && $key != "mode") {
					$this->$key = $value;
				}
			}
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

		// Get total rows
		if ($this->empty == false) {
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

		// Render widget
		return $this->render("application.widgets.views.Table", [
			"data" => $data,
			"parent" => get_class($this->widget)
		]);
	}
}