<?php

abstract class FormModel extends CFormModel {

	/**
	 * Override that variable to return list default
	 * validators aliases and it's replacement
	 * @var array - Array with replacements
	 * @see replace
	 */
	protected $replace = [
		"required" => "RequiredValidator"
	];

	/**
	 * Array with configuration (for future optimization)
	 * @var array - Array with configuration
	 * @deprecated - Unused
	 */
	protected $config = [];

	/**
	 * Construct form model with scenario and default data
	 * @param string $scenario - Name of the scenario that this model is used in
	 * @param array $data - Array with data
	 * @see getScenario
	 */
	public function __construct($scenario = "", $data = []) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
		parent::__construct($scenario);
	}

	/**
     * Override that method to return config. Config should return array associated with
     * model's variables. Every field must contains 3 parameters:
     *  + label - Variable's label, will be displayed in the form
     *  + type - Input type (@see _LFormInternalRender#render())
     *  + rules - Basic form's Yii rules, such as 'required' or 'numeric' etc
     * @return Array - Model's config
     */
    public abstract function config();

	/**
	 * Get configuration for specific key all for all form
	 * @param string $key - One configuration key name
	 * @return array - Array with configuration
	 */
	public function getConfig($key = null) {
		if (!$this->_config) {
			$this->_config = $this->config();
		}
		if ($key == null) {
			return $this->_config;
		}
		if (isset($this->_config[$key])) {
			return $this->_config[$key];
		} else {
			return null;
		}
	}

	/**
	 * Override that method to return list with replacements, don't
	 * forget parents replacements
	 * @return array - Array with replacements
	 */
	public function replace() {
		return $this->replace;
	}

	/**
	 * Override that method to return additional rule configuration, like
	 * scenario conditions or others
	 * @return array - Array with rule configuration
	 */
	public function backward() {
		return [
		];
	}

	/**
	 * Create filter for specific scenario to set fields
	 * without hidden property
	 * @param string|array $scenario - Name of scenario or array with scenarios
	 * @param array $allowed - Array with visible fields
	 * @return array - Row for backward method
	 * @see FormModel::backward
	 */
	public function createFilter($scenario, $allowed) {
		$locked = [];
		foreach (array_keys($this->getConfig()) as $key) {
			if (!in_array($key, $allowed)) {
				$locked[] = $key;
			}
		}
		return [ $locked, "hide", "on" => $scenario ];
	}

    /**
     * That function will check model's field for valid for drop down list
     * @param string $field - Field's name
     * @return bool - True if field can be used for data receive from db
     */
    public function isActive($field) {
        return isset($this->$field) && $this->$field && $this->$field != -1;
    }

	/**
	 * Check is field looks like drop down list
	 * @param $field - Name of field to test
	 * @return bool - True if field is drop down list
	 */
	public function isDropDown($field) {
		if (!$this->_config) {
			$this->_config = $this->getConfig();
		}
		if (!isset($this->_config[$field]) || (!isset($this->_config[$field]["type"]))) {
			return false;
		}
		$type = strtolower($this->_config[$field]["type"]);
		return $type == "dropdown" || $type == "multiple";
	}

	/**
	 * Build form from models configuration
	 * @param array|null $config - Array with model's configuration
	 * @throws CException
	 */
    private function buildConfig($config = null) {

        // If we have rules and labels then skip it
        if ($this->_rules && $this->_labels && $this->_types) {
            return;
        }

        // Get model's configuration
        if ($config == null) {
			$config = $this->getConfig();
        }

        // Reset rules and labels arrays
        $this->_rules = [];
        $this->_labels = [];
        $this->_types = [];
		$this->_strong = [];

		$this->_backward = $this->backward();

		foreach ($this->_backward as $i => &$b) {
			if (!isset($b[1]) || $b[1] != "hide") {
				continue;
			}
			$checked = $this->checkScenario($b["on"]);
			$keys = $b[0];
			if (is_array($keys)) {
				if ($checked) {
					foreach ($keys as $r) {
						$this->_config[$r]["hidden"] = true;
					}
				}
			} else if (is_string($keys)) {
				if ($checked) {
					$this->_config[$keys]["hidden"] = true;
				}
			} else {
				throw new CException("Illegal key type, found \"" . gettype($keys) . "\", require array or string");
			}
			array_splice($this->_backward, $i, 1);
		}

        foreach ($config as $key => &$field) {

            // Assign labels and rules arrays
            if (isset($field["label"])) {
                $this->_labels[$key] = $field["label"];
            } else {
                $this->_labels[$key] = "";
            }
            if (isset($field["rules"])) {
				$this->_rules = $this->buildRules($field["rules"], $key);
            }
            if (isset($field["types"])) {
                $this->_types[$key] = $field["types"];
            } else {
                $this->_types[$key] = "";
            }

            // Dynamically declare empty variable
			if (isset($field["value"])) {
				$this->$key = $field["value"];
			} else if (isset($this->$key) && !empty($this->$key)) {
				$field["value"] = $this->$key;
			} else {
				$this->$key = null;
			}
        }
    }

	/**
	 * Check if current model has been loaded with that
	 * one of that scenario
	 * @param array|string $scenario - Scenario name or array with names
	 * @return bool - True if scenario exists
	 */
	private function checkScenario($scenario) {
		if (is_array($scenario)) {
			foreach ($scenario as $s) {
				if (!strcasecmp($s, $this->scenario)) {
					return true;
				}
			}
		} else {
			return !strcasecmp($scenario, $this->scenario);
		}
		return false;
	}

	/**
	 * Find and replace validation rule for fields configuration
	 * @param string|array $rule - Rule options
	 * @return array|string - Sent rule options
	 */
	private function replaceRules(&$rule) {
		if (is_string($rule)) {
			foreach ($this->replace() as $old => $new) {
				$rule = str_replace($old, $new, $rule);
			}
			$result = [];
			foreach (explode(",", $rule) as $r) {
				$result[] = trim($r);
			}
			$rule = implode(",", $result);
		} else if (is_array($rule)) {
			foreach ($rule as &$r) {
				$this->replaceRules($r);
			}
		}
		return $rule;
	}

	/**
	 * Build rules array for CFormModel
	 * @param string|array $rules - Array with rules or simple string with imploded by comma rules
	 * @param string $key - Name of rules key
	 * @return array - Array with built rules
	 */
	private function buildRules($rules, $key) {
		$container = $this->_rules;
		if (is_string($rules)) {
			foreach (explode(",", $rules) as $i => $rule) {
				$rule = trim($rule);
				$this->replaceRules($rule);
				if (!isset($container[$rule])) {
					$container[$rule] = [];
				}
				array_push($container[$rule], $key);
			}
		} else if (is_array($rules)) {
			$this->_strong[] = array_merge([$key], $this->replaceRules($rules));
		}
		return $container;
	}

	/**
	 * Get data for key, that method - is result or optimization, when
	 * all data stuff was in basic configuration
	 * @param string $key - Name of unique field's identification number
	 * @return array - Array with data or null, if method hasn't been declared
	 * @deprecated - Use table configuration for it
	 */
	public function getKeyData($key) {
		$method = "get".self::changeNotation($key);
		if (method_exists($this, $method)) {
			return $this->$method();
		} else {
			return null;
		}
	}

	/**
	 * That function will change variable's notation from database's to
	 * classic PHP without '_' as delimiter. For example, name guide_id will
	 * be converted to GuideId
	 * @param string $name - Name to change
	 * @param bool $startWithUpper - Set that flag to false to set first letter to lower case
	 * @return string - Formatted string
	 */
	public static function changeNotation($name, $startWithUpper = true) {
		$result = "";
		foreach (explode("_", $name) as $word) {
			$result .= strtoupper(substr($word, 0, 1)) . substr($word, 1);
		}
		if (!$startWithUpper) {
			$result[0] = strtolower($result[0]);
		}
		return $result;
	}

    /**
     * Convert array with array models to list data, it will be faster
     * and easier in use then CHtml::listData method
     * @param array $models - Array with models
     * @param string $id - Name of select value
     * @param string $value - Name of select option text
     * @return array - Array with result map
	 * @see CHtml::listData
     */
    public static function listData($models, $id, $value) {
        $result = [];
        foreach ($models as &$model) {
            $result[$model[$id]] = $model[$value];
        }
        return $result;
    }

    /**
     * Get form model's rules associated with fields names
     * @return Array - Rules for form's mode;
     */
    public function rules() {
		if ($this->_cached != null) {
			return $this->_cached;
		}
		if (!$this->_rules) {
			$this->buildConfig();
		}
		$result = [];
		foreach ($this->_rules as $rule => $rules) {
			if (is_string($rules)) {
				array_push($result, [
					implode(", ", $rules), $rule
				]);
			} else {
				array_push($result, [
					$rules, $rule
				]);
			}
		}
		return $this->_cached = array_merge($result, array_merge($this->_strong,
			$this->replaceRules($this->_backward)
		));
	}

	private $_cached = null;

    /**
     * Get form model's labels
     * @return Array - Array with labels associated with fields names
     */
    public function attributeLabels() {
        if (!$this->_labels) {
            $this->buildConfig();
        }
        return $this->_labels;
    }

	/**
	 * Get rules from [rules] method
	 * @return array - Array with rules
	 */
	public function getRules() {
		return $this->_rules;
	}

	protected $_rules = null;
	protected $_strong = null;
	protected $_labels = null;
	protected $_types = null;
	protected $_config = null;
	protected $_data = null;
	protected $_backward = null;
}