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
	public function createUrl($query = [], $action = "getWidget") {
		return preg_replace("/\\/[a-z0-9]*$/i", "/$action", $this->getController()->createUrl("", $query));
	}

	/**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
    public function run() {
        $this->render(get_called_class(), null, false);
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