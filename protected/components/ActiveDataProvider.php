<?php

abstract class ActiveDataProvider extends CActiveDataProvider {

	const PAGE_SIZE = 25;

	/**
	 * @var array|false with extra information about columns that should be
	 *  fetched, it takes information about columns from active record configuration
	 *
	 * One query maybe associated with several models, so it might has
	 * one of next structures:
	 *
	 * 1. string name of active record class (string)
	 * 2. array with names, where key is index of model (string[])
	 * 3. array with configured models, where every field
	 * 	associated with it's model (array key is class name of AR)
	 *
	 * Example:
	 *
	 * <code>
	 *
	 * // Fetch as name of model class
	 * public $fetcher = 'app\models\User';
	 *
	 * // Fetcher as list with possible models
	 * public $fetcher = [
	 * 		'app\models\User',
	 * 		'app\models\Role'
	 * ];
	 *
	 * // Fetcher as configured fields
	 * public $fetcher = [
	 * 		'app\models\User' => [
	 * 			"id", "login", "email"
	 * 		],
	 * 		'app\models\Role' => [
	 * 			"name", "description"
	 * 		]
	 * ];
	 *
	 * </code>
	 */
	public $fetcher = false;

	/**
	 * @var CDbCriteria|string|array with extra criteria parameters for CDbCriteria
	 * 	class. It may be class instance, serialized object or array with config
	 */
	public $condition = null;

	/**
	 * Override that method to return class name or
	 * instance of active record class for current active
	 * data provider
	 *
	 * @return CActiveRecord|string
	 */
	public abstract function model();

	/**
	 * Override that action to return configuration for active data
	 * provider class (self instance), it uses to produce more operations
	 * into class on server side
	 */
	public function search() {
		return [
			'pagination' => [
				'pageSize' => static::PAGE_SIZE
			],
			'sort' => [
				'attributes' => [ 'id' ],
				'defaultOrder' => [
					'id' => CSort::SORT_ASC
				]
			]
		];
	}

	/**
	 * Construct class instance with configuration
	 *
	 * @param $config array with class configuration
	 */
	public function __construct($config = []) {
		# We should copy soft items into data provider
		foreach ($config as $key => $value) {
			if (!$this->hasProperty($key)) {
				$this->$key = $value;
			}
		}
		# Then we constructs it with merged search parameters and config
		parent::__construct($this->model(), CMap::mergeArray(
			$this->search(), $this->config = $config
		));
		# Merge condition with criteria
		if ($this->getCriteria() != null && $this->condition != null) {
			$this->getCriteria()->mergeWith($this->condition);
		}
        # Build condition SQL string
        if (!empty($this->condition['condition']) && !empty($this->condition['params'])) {
            $params = $this->condition['params'];
            foreach ($params as $key => &$value) {
                if (is_string($value)) {
                    $value = "'$value'";
                }
            }
            $this->condition['condition'] = strtr(
                $this->condition['condition'], $params
            );
            unset($this->condition['params']);
            $params = $this->config['condition']['params'];
            foreach ($params as $key => &$value) {
                if (is_string($value)) {
                    $value = "'$value'";
                }
            }
            $this->config['condition']['condition'] = strtr(
                $this->config['condition']['condition'], $params
            );
            unset($this->config['condition']['params']);
        }
	}

	/**
	 * @var array with data provider configuration, which
	 *  copies to widget which uses it
	 *
	 * @internal only for internal usage
	 */
	public $config = [];

	/**
	 * Get new or cached instance of fetcher class, which providers
	 * action with data fetch from another foreign tables or
	 * simple dropdown lists
	 *
	 * @return Fetcher class instance
	 */
	public function getFetcher() {
        return $this->_fetcher == null ? $this->_fetcher = new Fetcher([
            'fetcher' => $this->fetcher
        ]) : $this->_fetcher;
	}

	private $_fetcher = null;
} 