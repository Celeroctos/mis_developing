<?php

abstract class ActiveRecord extends CActiveRecord {

	/**
	 * Get model's instance from cache
	 * @param string $className - Class's name
	 * @return static - Cached model instance
	 */
	public static function model($className = null) {
		if ($className == null) {
			$className = get_called_class();
		}
		return parent::model($className);
	}

	/**
	 * Load model from form model instance and return it
	 * @param FormModel $formModel - Form model instance with
	 *    attributes that should be copied to model
	 * @param array $data - Extra form fields
	 * @return static - New just created instance with
	 *    form model attributes
	 */
	public static function loadFromModel(FormModel $formModel, $data = []) {
		$self = new static();
		if ($formModel !== null) {
			foreach ($formModel->getAttributes() as $key => $value) {
				$self->$key = $value;
			}
		}
		foreach ($data as $key => $value) {
			$self->$key = $value;
		}
		return $self;
	}

	public static function load($data = []) {
		$self = new static();
		foreach ($data as $key => $value) {
			$self->$key = $value;
		}
		return $self;
	}

	/**
	 * This method is invoked after saving a record successfully.
	 * The default implementation raises the {@link onAfterSave} event.
	 * You may override this method to do postprocessing after record saving.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 */
	protected function afterSave() {
		parent::afterSave();
		try {
			$pk = Yii::app()->getDb()->getLastInsertID(
				$this->tableName()."_id_seq"
			);
			if (!empty($this->tableSchema->primaryKey)) {
				$this->{$this->tableSchema->primaryKey} = $pk;
			} else {
				$this->{$this->primaryKey} = $pk;
			}
		} catch (Exception $ignored) {
			/* We can't be sure, that we've just inserted new row in db */
		}
	}

	/**
     * Override that method to return list with table
     * keys for GridView widget
     * @return array - Array with keys names
     */
    public function getKeys() {
        return [];
    }

    /**
     * Override that method to return data for grid view, main table
	 * must have 't' alias
	 * @return CDbCommand - Command with query
     * @throws CDbException
     */
    public function getGridViewQuery() {
        return $this->getDbConnection()->createCommand()
            ->select("*")->from($this->tableName() . " as t");
    }

    /**
     * Returns the attribute labels.
     * Attribute labels are mainly used in error messages of validation.
     * By default an attribute label is generated using {@link generateAttributeLabel}.
     * This method allows you to explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions like array_merge().
     *
     * @return array attribute labels (name=>label)
     * @see generateAttributeLabel
     */
    public function attributeLabels() {
        return $this->getKeys();
    }

	/**
     * Find elements and format for drop down list
     * @param string $condition - List with condition
     * @param array $params - Query's parameters
     * @param string $pk - Name of primary key (or another value)
     * @return array - Array where every row associated with it's id
     */
	public function findForDropDown($condition = '', $params = array(), $pk = "id") {
        $result = $this->getDbConnection()->createCommand()
            ->select("*")
            ->from($this->tableName())
            ->where($condition, $params)
            ->queryAll();
		$select = [];
		foreach ($result as $r) {
			$select[$r[$pk]] = $this->populateRecord($r);
		}
		return $select;
	}

	/**
	 * Prepare array to drop down list
	 * @param array $array - Array with query results
	 * @param string $pk - Primary key name
	 * @return array - Array where every row associated with it's primary key
	 */
	public function toDropDown(array $array, $pk = "id") {
		$select = [];
		foreach ($array as $r) {
			if (is_array($r)) {
				$r = $this->populateRecord($r);
			}
			$select[$r->$pk] = $r;
		}
		return $select;
	}

	/**
	 * Prepare array to drop down list
	 * @param array $array - Array with query results
	 * @param string $pk - Primary key name
	 * @return array - Array where every row associated with it's primary key
	 */
	public static function toDropDownStatic(array $array, $pk = "id") {
		$select = [];
		foreach ($array as $r) {
			if (is_array($r)) {
				$f = $r[$pk];
			} else {
				$f = $r->$pk;
			}
			$select[$f] = $r;
		}
		return $select;
	}

    /**
     * Find all identification numbers for this table
     * @param string $conditions - Search condition
     * @param array $params - Array with parameters
     * @param string $pk - Primary key
     * @throws CDbException
     * @return array - Array with identification numbers
     */
	public function findIds($conditions = '', $params = [], $pk = "id") {
		$query = $this->getDbConnection()->createCommand()
			->select($pk)
			->from($this->tableName())
			->where($conditions, $params);
		$array = [];
		foreach ($query->queryAll() as $a) {
			$array[] = $a[$pk];
		}
		return $array;
	}

	/**
	 * Convert array with active records or arrays to list with ids
	 * @param array $array - Array with models
	 * @param string $pk - Primary key name
	 * @return array - List with ids
	 */
	public static function getIds(array $array, $pk = "id") {
		$result = [];
		foreach ($array as $r) {
			if (is_array($r)) {
				$result[] = $r[$pk];
			} else {
				$result[] = $r->$pk;
			}
		}
		return $result;
	}

    /**
     * Get data provider for CGridView widget
     * @return CActiveDataProvider - Data provider
     */
    public function getDataProvider() {
        $criteria = new CDbCriteria();
        foreach ($this->getKeys() as $key => $ignored) {
            $criteria->compare($key, $this->$key, true, '');
        }
        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => [
                    'id' => CSort::SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);
    }

	/**
	 * Get instance of default table provider for current table
	 * @return TableProvider - Default table provider
	 */
	public function getDefaultTableProvider() {
		return new TableProvider($this);
	}
}