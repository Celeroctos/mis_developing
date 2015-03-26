<?php

class AutoForm extends Widget {

    public $id = null;
    public $url = null;

    /**
     * @var FormModel - Form's model
     */
    public $model = null;

	/**
	 * @var bool - Shall display labels?
	 */
	public $labels = true;

    /**
     * Override that method to return just rendered component
     * @throws CException
     * @return string - Just rendered component or nothing
     */
    public function run() {
		if ($this->id == null) {
			$this->id = UniqueGenerator::generate("form");
		}
        if (is_array($this->model)) {
            $config = [];
            foreach ($this->model as $i => $model) {
                if ($this->test($model)) {
                    $config += $model->getConfig();
                }
            }
            $this->model = new FormModelAdapter($config);
        } else {
            $this->test($this->model);
        }
        $this->render(__CLASS__, [
            "model" => $this->model,
            "class" => __CLASS__
        ]);
    }

    /**
     * Test model for FormModel inheritance and not null
     * @param Mixed $model - ActiveRecord which must extends FormModel
     * @return bool - True if everything ok
     * @throws CException
     */
    private function test($model) {
        if (!$model || !($model instanceof FormModel)) {
            throw new CException("Unresolved model field or form model isn't instance of FormModel ".(int)$model);
        }
        return true;
    }

    /**
     * Format every data field with specific format, it will get data format field's
     * from model
     * @param String $format - String with data format, for example ${id} or ${surname}
     * @param Array $data - Array with data to format
     */
    public static function format($format, array& $data) {
        foreach ($data as $i => &$value) {
			if (is_object($value)) {
				$model = clone $value;
			} else {
				$model = $value;
			}
            $matches = [];
            if (is_string($format)) {
                preg_match_all("/%\\{([a-zA-Z_0-9]+)\\}/", $format, $matches);
                $value = $format;
                if (count($matches)) {
                    foreach ($matches[1] as $m) {
                        $value = preg_replace("/%\\{([({$m})]+)\\}/", $model[$m], $value);
                    }
                }
            } else if (is_callable($format)) {
                $value = $format($value);
            }
        }
    }

    /**
     * Fetch data for current table configuration, it will
     * throw an exception if value or name won't be defined, where
     *  + key - Name of table's primary key
     *  + value - Name of table's value to display
     *  + name - Name of displayable table
     * @param array $table - Array with table configuration
     * @return array - Array with prepared data
     * @throws CException
     */
    public static function fetch($table) {
        if (!isset($table["name"]) && !isset($table["value"])) {
            throw new CException("Table configuration requires key, value and name");
        }
        if (!isset($table["key"])) {
            $table["key"] = "id";
        }
        $key = $table["key"];
        $value = $table["value"];
		if (isset($table["group"]) && $table["group"]) {
			$query = Yii::app()->getDb()->createCommand()
				->select("max($key) as $key, $value")
				->from($table["name"])
				->group($value);
		} else {
			$query = Yii::app()->getDb()->createCommand();
			$query->select("$key, $value");
			$query->from($table["name"]);
		}
		if (isset($table["order"]) && $table["order"]) {
			$query->order($table["order"]);
		}
		$data = $query->queryAll();
        $result = [];
        if (isset($table["format"])) {
            foreach ($data as $row) {
                $result[$row[$key]] = $row;
            }
            static::format($table["format"], $result);
        } else {
            foreach ($data as $row) {
                $result[$row[$key]] = $row[$value];
            }
        }
        return $result;
    }

	/**
	 * Get configuration option or it's default value
	 * @param array $config - Array with configuration
	 * @param string $key - Name of key to get
	 * @param mixed $default - Default value
	 * @return mixed - It's value or default
	 */
	private function option(array& $config, $key, $default = null) {
		if (isset($config[$key])) {
			return $config[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Prepare field for render, it will build it's configuration
	 * and return Field instance
	 * @param string $key - Name of field's key (type)
	 * @return Field - Field instance
	 */
	public function prepare($key) {
		$config = $this->model->getConfig($key);
		$options = $this->option($config, "options", []);
		if (isset($config["update"])) {
			$options["data-update"] = $config["update"];
		}
		if (isset($config["table"])) {
			$data = $this->fetch($config["table"]);
		} else {
			$data = [];
		}
		$type = $this->option($config, "type", "text");
		$value = $this->option($config, "value");
		$label = $this->option($config, "label", "");
		return FieldCollection::getCollection()->find($type)
			->bind($key, $label, $value, $data, $options);
	}

	/**
	 * That function will render all form elements based on it's type
	 * @param CActiveForm $form - Active form instance
	 * @param string $key - Field name
	 * @return string - Result string
	 * @throws ErrorException - If field's type hasn't been implemented in renderer
	 */
	public function renderField($form, $key) {
		return $this->prepare($key)->render($form, $this->model);
	}

	/**
	 * Render form control element
	 * @param string $class - Main class name
	 * @param array $control - Array with single control configuration
	 * @return string - Rendered element
	 */
	public function renderControl($class, array $control) {
		return CHtml::tag(isset($control["tag"]) ? $control["tag"] : "button", isset($control["text"]) ? $control["text"] : "", [
			"class" => (isset($control["class"]) ? $control["class"] : "btn btn-primary") . " $class",
		] + isset($control["options"]) ? $control["options"] : []);
	}

    /**
     * Check model's type via it's configuration
     * @param string $key - Name of native key to check
     * @param string $type - Type to test
     * @return bool - True if type if equal else false
     */
    public function checkType($key, $type) {
        $config = $this->model->getConfig()[$key];
        if (!isset($config["type"])) {
            $config["type"] = "text";
        }
        return strtolower($config["type"]) == strtolower($type);
    }

	/**
	 * Check if field has hidden property
	 * @param string $key - Name of native key to check
	 * @return bool - True if field must be hidden
	 */
	public function getForm($key) {
		$config = $this->model->getConfig()[$key];
		if (!isset($config["form"])) {
			return false;
		}
		return $config["form"];
	}

    /**
     * Check if field has hidden property
     * @param string $key - Name of native key to check
     * @return bool - True if field must be hidden
     */
    public function isHidden($key) {
        $config = $this->model->getConfig()[$key];
        if (!isset($config["hidden"])) {
            return false;
        }
        return $config["hidden"];
    }
} 