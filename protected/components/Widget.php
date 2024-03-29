<?php

class Widget extends CWidget {

    /**
     * Override that method to return just rendered component
     * @param bool $return - If true, then widget shall return rendered component else it should print to output stream
     * @return string - Just rendered component or nothing
     */
    public function call($return = true) {
        if ($return) {
            ob_start();
        }
        $this->run();
        if ($return) {
            return ob_get_clean();
        }
        return null;
    }

	/**
	 * Serialize widget's attributes by all scalar attributes and
	 * arrays or set your own array with attribute names
	 *
	 * Agreement: I hope that you will put serialized attributes
	 *    in root widget's HTML tag named [data-attributes]
	 *
	 * @param array|null $attributes - Array with attributes, which have
	 *    to be serialized, by default it serializes all scalar attributes
	 *
	 * @param array|null $excepts - Array with attributes, that should
	 *    be excepted
	 *
	 * @param array|null $string - Array with parameters that should
	 *      be converted to string value
	 *
	 * @return string - Serialized and URL encoded attributes
	 * @throws Exception
	 */
	public function getAttributes($attributes = null, $excepts = null, $string = null) {
		$params = [];
		if ($attributes === null) {
			$attributes = $this;
		}
        if ($excepts == null) {
            $excepts = [ 'skin' ];
        }
		foreach ($attributes as $key => $value) {
			if (in_array($key, $excepts) || $key === "_config") {
				continue;
			}
			if ((is_scalar($value) || is_array($value)) && !empty($value)) {
				$params[$key] = $value;
			} else if (is_object($value) && $value instanceof CActiveRecord && is_string($value->getTableSchema()->primaryKey)) {
                $params[$key] = $value->getAttribute($value->getTableSchema()->primaryKey);
            } else if ($string !== null && in_array($key, $string)) {
				if (is_object($value)) {
                    $params[$key] = get_class($value);
				} else if (is_scalar($value)) {
					$params[$key] = (string) $value;
				} else {
					throw new Exception("Unknown type can't be converted to string \"". gettype($value) ."\"");
				}
			}
		}
		return htmlspecialchars(json_encode($params));
	}

	/**
	 * Get widget's identification value or generate new
	 * @param bool $autoGenerate - Is identification values should
	 * 	be auto generated
	 * @return null|string - Current widget's id, just generated or null
	 */
	public function getId($autoGenerate = true) {
		if($this->id !== null) {
			return $this->id;
		} else if ($autoGenerate) {
			return $this->id = UniqueGenerator::generate(
				strtolower(get_called_class())
			);
		} else {
			return null;
		}
	}

	/**
	 * Create widget for current instance by it's static
	 * context
	 * @param string $config - Widget's configuration
	 */
	public static function runWidget($config) {
		self::createWidget(get_called_class(), $config)->run();
	}

	/**
	 * Create url for widget's update for current module and controller
	 * @param array $query - Additional query GET parameters
	 * @param string $action - Action for new URL
	 * @return string - Url for widget update
	 */
	public static function createUrl($action = "getWidget", $query = []) {
		return preg_replace("/\\/[a-z0-9]*$/i", "/$action", Yii::app()->getController()->createUrl("", $query));
	}

	/**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
    public function run() {
    }

    /**
     * Render widget and return it's just rendered component
     * @param string $class - Path to widget to render
     * @param array $properties - Widget's properties
     * @return mixed|void
     */
    public function getWidget($class, $properties = []) {
        return $this->widget($class, $properties, true);
    }
} 