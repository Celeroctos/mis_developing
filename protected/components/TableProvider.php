<?php

class TableProvider extends CComponent {

	/**
	 * @var CActiveRecord|string - Database active
	 * 	record instance or it's class name
	 */
	public $activeRecord = null;

	/**
	 * @var TablePagination|false - Table pagination
	 *	instance, set it to false to disable pagination
	 */
	public $pagination = null;

	/**
	 * @var CDbCommand - Query to fetch data from
	 * 	database table for current provider
	 */
	public $fetchQuery = null;

	/**
	 * @var CDbCommand - Query to fetch count of
	 * 	database table's rows for current provider
	 */
	public $countQuery = null;

	/**
	 * @var string - Order by cause
	 */
	public $orderBy = null;

	/**
	 * Construct table provider with [tableName] check
	 * @param CActiveRecord|string $activeRecord - Active record instance
	 *    or class name
	 * @param CDbCommand $fetchQuery - Query to fetch rows from
	 *    database's table
	 * @param CDbCommand $countQuery - Query to count rows from
	 *    database's table
	 * @throws CException
	 * @see activeRecord
	 */
	public function __construct($activeRecord = null, $fetchQuery = null, $countQuery = null) {
		if (($this->activeRecord = $activeRecord) == null) {
			throw new CException("Table provider can't resolve [null] active record instance");
		}
		if (is_string($this->activeRecord)) {
			$this->activeRecord = new $this->activeRecord();
		}
		if ($fetchQuery == null) {
			$this->fetchQuery = $this->getFetchQuery();
		} else {
			$this->fetchQuery = $fetchQuery;
		}
		if ($countQuery == null) {
			$this->countQuery = $this->getCountQuery();
		} else {
			$this->countQuery = $countQuery;
		}
		if ($this->pagination !== false) {
			$this->pagination = $this->getPagination();
		}
	}

	/**
	 * Override that method to return command for table widget
	 * @return CDbCommand - Command with selection query
	 * @throws CDbException
	 */
	public function getFetchQuery() {
		if ($this->fetchQuery !== null) {
			return $this->fetchQuery;
		}
		return $this->getDbConnection()->createCommand()
			->select("*")
			->from($this->activeRecord->tableName());
	}

	/**
	 * Override that method to return count of rows in table
	 * @return CDbCommand - Command to get count of rows
	 */
	public function getCountQuery() {
		if ($this->countQuery !== null) {
			return $this->countQuery;
		}
		return $this->getDbConnection()->createCommand()
			->select("count(1) as count")
			->from($this->activeRecord->tableName());
	}

	/**
	 * Fetch rows from query
	 * @return array - Array with fetched data
	 */
	public function fetchData() {
		$this->fetchQuery = $this->getDbConnection()->createCommand()
			->select("*")->from("(". $this->fetchQuery->getText() .") as _");
		$this->countQuery = $this->getDbConnection()->createCommand()
			->select("*")->from("(". $this->countQuery->getText() .") as _");
		if ($this->getPagination()->optimizedMode) {
			$this->applyCriteria($this->getCriteria(), function($query) {
				/** @var $query CDbCommand */
				$query->limit($this->getPagination()->pageLimit + 1,
					$this->getPagination()->pageLimit * $this->getPagination()->currentPage
				);
			});
		} else {
			$this->applyCriteria($this->getCriteria());
		}
		if (($row = $this->countQuery->queryRow()) != null) {
			$count = $row["count"];
		} else {
			$count = 0;
		}
		$this->getPagination()->calculate($count);
		$this->fetchQuery->limit($this->getPagination()->getLimit(),
			$this->getPagination()->getOffset()
		);
		return $this->fetchQuery->queryAll();
	}

	/**
	 * Apply criteria to provider's query
	 * @param CDbCriteria $criteria - Database criteria
	 * @param callable $custom - Custom function for count query
	 * @return CDbCriteria - Same criteria instance
	 */
	public function applyCriteria($criteria, $custom = null) {
		if ($custom == null) {
			$queries = [
				$this->countQuery,
				$this->fetchQuery
			];
		} else {
			$queries = [
				$this->fetchQuery
			];
			$custom($this->countQuery);
		}
		foreach ($queries as $query) {
			/** @var $query CDbCommand */
			$query->where($criteria->condition, $criteria->params);
		}
		$this->fetchQuery->order($criteria->order)
			->order($this->orderBy);
		return $criteria;
	}

	/**
	 * Get criteria for current provider
	 * @return CDbCriteria|null - Database criteria
	 */
	public function getCriteria() {
		if ($this->_criteria == null) {
			return $this->_criteria = CriteriaAdapter::createOrderBy(
				$this->activeRecord->tableSchema->primaryKey
			);
		} else {
			return $this->_criteria;
		}
	}

	/**
	 * Get pagination for current provider
	 * @return null|TablePagination - Table pagination
	 */
	public function getPagination() {
		if ($this->_pagination == null) {
			return $this->_pagination = new TablePagination();
		} else {
			return $this->_pagination;
		}
	}

	/**
	 * Get singleton database connection
	 * @return CDbConnection - Database connection
	 */
	public function getDbConnection() {
		return Yii::app()->getDb();
	}

	private $_criteria = null;
	private $_pagination = null;
}