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
	 * Create url for widget's update for current module and controller
	 * @param array $query - Additional query GET parameters
	 * @return string - Url for widget update
	 */
	public function createUrl($query = []) {
		return preg_replace("/\\/[a-z0-9]*$/i", "/getWidget", $this->getController()->createUrl("", $query));
	}

	/**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
    public function run() {
        $this->render(__CLASS__, null, false);
    }

    /**
     * Try to get default value for some field
     * @param string $key - Value's key
     * @return mixed - Default value or null
     */
    public function getDefault($key) {
        if (isset($this->_model[$key])) {
            return $this->_model[$key];
        }
        return null;
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