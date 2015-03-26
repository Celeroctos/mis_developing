<?php

class DbCriteria extends CDbCriteria {

    /**
     * Create database criteria with 'where' cause
     * @param array|string $condition - Condition
     * @param array $params - Array with parameters
     * @return static
     */
    public static function createWithWhere($condition, $params = []) {
        $criteria = new static();
        $criteria->condition = $condition;
        $criteria->params = $params;
        return $criteria;
    }
} 