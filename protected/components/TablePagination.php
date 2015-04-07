<?php

class TablePagination extends CComponent {

	/**
	 * @var int - Number of current page
	 */
	public $currentPage = 1;

	/**
	 * @var int - Count of total pages
	 */
	public $totalPages;

	/**
	 * @var int - Maximum page limit per page
	 */
	public $pageLimit = 25;

	/**
	 * Construct table pagination with count of rows
	 * @param int $totalCount - Total count of rows
	 */
	public function __construct($totalCount = null) {
		if ($totalCount != null) {
			$this->calculate($totalCount);
		}
	}

	/**
	 * Calculate pagination pages and it's offsets
	 * @param int $totalCount - Total count of rows
	 */
	public function calculate($totalCount) {
		if (!($this->totalPages = intval($totalCount / $this->pageLimit + ($totalCount / $this->pageLimit * $this->pageLimit != $totalCount ? 1 : 0)))) {
			$this->totalPages = 1;
		}
	}

	/**
	 * Calculate page offset for some page, for null page
	 * current page will be token
	 * @param int $page - Current page
	 * @return int - The index where page starts
	 * @throws CException
	 */
	public function getOffset($page = null) {
		if ($page === null && ($page = $this->currentPage) === null) {
			throw new CException("Can't calculate pagination offset without current page");
		} else {
			return $this->pageLimit * ($page - 1);
		}
	}

	/**
	 * Get count of displayable rows per page
	 * @return int - Count of rows to display
	 */
	public function getLimit() {
		return $this->pageLimit;
	}
}