<?php

class LGridView extends LWidget {

    /**
     * @var string - Primary client's component identifier
     */
    public $id = null;

    /**
     * @var ActiveRecord|string - Model to render, it must be instance of Model class
     */
    public $model = null;

	/**
	 * @var string - Default model scenario
	 */
	public $scenario = "";

    /**
     * @var string - Basic table class
     */
    public $class = "table table-bordered table-striped table-condensed";

    /**
     * @var array - Array with columns to display, if its null, then it
     *      will take array with columns from getKeys method
     * @see LModel::getKeys
     */
    public $columns = null;

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run() {
		if (is_string($this->model)) {
			$this->model = new $this->model();
		}
        if (!($this->model instanceof ActiveRecord)) {
            throw new CException("Model must be instance of ActiveRecord class");
        }
		if ($this->model->getScenario() == "") {
			$this->model->setScenario($this->scenario);
		}
        if (empty($this->columns)) {
            $this->columns = [];
            foreach ($this->model->getKeys() as $key => $ignored) {
                $this->columns[] = [
                    "name" => $key
                ];
            }
        }
        $this->render(__CLASS__, [
            "model" => $this->model
        ]);
    }
}