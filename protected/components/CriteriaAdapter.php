<?php

class CriteriaAdapter extends CDbCriteria implements Adapter {

	/**
	 * Create database criteria with 'where' cause
	 * @param array|string $condition - Condition
	 * @param array $params - Array with parameters
	 * @param string $order - Order string
	 * @return static
	 */
    public static function createWhere($condition, $params = [], $order = "") {
        $criteria = new static();
        $criteria->condition = $condition;
        $criteria->params = $params;
		$criteria->order = $order;
        return $criteria;
    }

	/**
	 * Create database criteria with 'order by' cause
	 * @param string $order - Order string
	 * @return static
	 */
	public static function createOrderBy($order = "") {
		$criteria = new static();
		$criteria->order = $order;
		return $criteria;
	}
} 