<?php

class Fetcher extends CComponent {

	const DEFAULT_KEY = "id";

	public function __construct($config = []) {
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Create new instance of fetcher with single
	 * fetcher's parameter
	 *
	 * @param $fetcher array|string with fetcher
	 * 	configuration, [@see fetcher]
	 *
	 * @return static
	 */
	public static function create($fetcher) {
		return new static([ "fetcher" => $fetcher ]);
	}

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
	 * Fetch extra information using fetch configuration, it
	 * has 3 main strategy of method work, see doc bellow
	 * to get more information about it
	 *
	 * @param $models array reference to input model
	 * 	with ActiveRecord component
	 *
	 * @throws Exception
	 */
	public function fetch(array& $models) {
		if (empty($this->fetcher)) {
			throw new Exception("Fetcher can't be empty");
		}
		if (is_array($this->fetcher)) {
			if (array_key_exists(0, $this->fetcher)) {
				foreach (array_reverse($this->fetcher) as $f) {
					$this->fetch($models, $f);
				}
			} else {
				foreach ($this->fetcher as $f => $fields) {

				}
			}
		} else if (is_string($this->fetcher)) {
			$ar = new $this->fetcher();
			if (!$ar instanceof FormModel) {
				throw new Exception("Fetcher must be an instance of [app\\core\\ActiveRecord] class");
			}
			$config = $ar->getConfig();
			foreach ($models as &$model) {
				foreach ($config as $key => $value) {
					if (!isset($model->$key) || !isset($value["table"])) {
						continue;
					}
					$data = $this->fetchTable($value["table"]);
					if (($offset = $model[$key]) != null && isset($data[$offset])) {
						$model[$key] = $data[$offset];
					} else {
						$model[$key] = "Нет";
					}
				}
			}
		} else {
			throw new Exception("Fetcher must be string or array, read doc for more information");
		}
	}

	/**
	 * Fetch information from database model with
	 * table configuration
	 *
	 * Where configuration is:
	 *  + name - name of table in database with it's schema
	 *  + key - name of table's column with primary key constraint
	 *  + value - list with values to fetch (you can use comma to separate values)
	 *  + [format] - special format for row, use %{<column>} pattern for column alias
	 *
	 * @param $config array with table
	 * 	configuration
	 *
	 * @return array with result
	 * @throws Exception
	 */
	public static function fetchTable(array $config) {
		$query = Yii::app()->getDb()->createCommand();
		if (!isset($config["name"]) && !isset($config["value"])) {
			throw new Exception("Name and value is required table configuration parameters");
		}
		if (isset($config["key"])) {
			$key = $config["key"];
		} else {
			$key = static::DEFAULT_KEY;
		}
		$table = $config["name"];
		$value = $config["value"];
		if (isset($config["group"])) {
			if (!empty($config["group"])) {
				$query->select("max($key) as key, $value")
					->from($table)->group($value);
			} else {
				throw new Exception("Table group parameter can't be empty");
			}
		} else {
			$query->select("$key, $value")
				->from($table);
		}
		if (isset($config["order"])) {
			if (!empty($config["order"])) {
				$query->order($config["order"]);
			} else {
				throw new Exception("Table order parameter can't be empty");
			}
		}
		$data = $query->queryAll();
		$result = [];
		if (isset($config["format"])) {
			foreach ($data as $row) {
				$result[$row[$key]] = $row;
			}
			static::format($config["format"], $result);
		} else {
			foreach ($data as $row) {
				$result[$row[$key]] = $row[$value];
			}
		}
		return $result;
	}

	/**
	 * Format every data field with specific format, it will get data
	 * format field's from model
	 *
	 * @param $format string with data format, for example ${id} or ${surname}
	 * @param $data array with data to format
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function format($format, array& $data) {
		foreach ($data as $i => &$value) {
			if (is_object($value)) {
				$model = clone $value;
			} else {
				$model = $value;
			}
			$matches = [];
			if (is_callable($format)) {
				$value = $format($value);
			} else if (!is_string($format)) {
				throw new \InvalidArgumentException("Format must be string or function");
			}
			preg_match_all("/%\\{([a-zA-Z_0-9]+)\\}/", $format, $matches);
			$value = $format;
			if (!count($matches)) {
				continue;
			}
			foreach ($matches[1] as $m) {
				$value = preg_replace("/\\%{{$m}}/", $model[$m], $value);
			}
		}
	}
}