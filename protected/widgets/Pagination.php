<?php

class Pagination extends Widget {

	/**
	 * @var int - Current page number, default
	 * 	value is first page
	 */
	public $currentPage = 1;

	/**
	 * @var int - Total count of pages for
	 * 	current query
	 */
	public $totalPages = 0;

	/**
	 * @var int - Maximum of displayed rows per
	 * 	one page
	 */
	public $pageLimit = 10;

	/**
	 * @var TablePagination - Table pagination instance, if
	 * 	not null, the upper values get it
	 */
	public $tablePagination = null;

	/**
	 * @var callback - Callback function which sets page
	 * 	change pagination action
	 */
	public $clickAction = null;

	/**
	 * Get string for <li> with onclick action
	 * @param $condition - Boolean condition result
	 * @param $accumulator - Accumulation value
	 * @return string - Result string
	 */
	public function getClick($condition = true, $accumulator = 0) {
		$page = $this->currentPage + $accumulator;
		if ($condition && is_callable($this->clickAction)) {
			return "onclick=\"".call_user_func($this->clickAction, $page)."\"" . (
				$page == $this->currentPage ? "class=\"active\"" : ""
			);
		} else {
			return "class=\"disabled\"";
		}
	}

	/**
	 * Run widget to return just rendered content
	 * @return string - Just rendered content
	 */
	public function run() {
		if ($this->tablePagination !== null) {
			foreach ($this->tablePagination as $key => $value) {
				$this->$key = $value;
			}
		}
		$offset = $this->pageLimit - $this->totalPages + $this->currentPage;
		return $this->render(__CLASS__, [
			"offset" => $offset > 0 ? -$offset : 0,
			"step" => $offset < 0 ? 1 : -1
		]);
	}
}