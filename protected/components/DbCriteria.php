<?php

class DbCriteria extends CDbCriteria {

	/**
	 * Create database criteria with 'where' cause
	 * @param array|string $condition - Condition
	 * @param array $params - Array with parameters
	 * @param string $order - Order string
	 * @return static
	 */
    public static function createWithWhere($condition, $params = [], $order = "") {
        $criteria = new static();
        $criteria->condition = $condition;
        $criteria->params = $params;
		$criteria->order = $order;
        return $criteria;
    }
} 