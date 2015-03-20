<?php

class ActiveDataProvider extends CActiveDataProvider {

	/**
	 * @var FormModel - Set your form model to cast all ids and
	 * 	drop down key to it's readable value
	 */
	public $form = null;

    /**
     * Fetches the data from the persistent data storage.
     * @return array - List of data items
     */
    protected function fetchData() {
        $criteria = clone $this->getCriteria();
        if(($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
            $pagination->applyLimit($criteria);
        }
        $baseCriteria = $this->model->getDbCriteria(false);
        if(($sort = $this->getSort()) !== false) {
            if($baseCriteria !== null) {
                $c = clone $baseCriteria;
                $c->mergeWith($criteria);
                $this->model->setDbCriteria($c);
            } else {
                $this->model->setDbCriteria($criteria);
            }
            $sort->applyOrder($criteria);
        }
        $this->model->setDbCriteria($baseCriteria !== null ? clone $baseCriteria : null);
		if ($this->model instanceof ActiveRecord) {
			if (($data = $this->model->getGridViewData()) != null && isset($data[0]) && !($data[0] instanceof CActiveRecord)) {
				$data = $this->model->populateRecords($data);
			}
			if ($this->form != null) {
				foreach ($data as &$row) {
					$this->fetchExtraData($row);
				}
			}
		} else {
			$data = $this->model->findAll($criteria);
		}
        $this->model->setDbCriteria($baseCriteria);
        return $data;
    }

	/**
	 * Fetch extra data from rows and database by model's form
	 * @param mixed $row - Array with fetched data
	 * @throws CException
	 */
	private function fetchExtraData(&$row) {
		foreach ($this->form->getConfig() as $key => $config) {
			if ($config["type"] != "DropDown" || !isset($config["table"])) {
				$field = FieldCollection::getCollection()->find($config["type"], false);
				if ($field != null && $field instanceof DropDown) {
					$row[$key] = $field->getData($row[$key]);
				}
			} else {
				$row[$key] = AutoForm::fetch($config["table"]);
			}
		}
	}

    /**
     * Get model class instance
     * @return ActiveRecord - Model class instance
     */
    public function getModel() {
        return $this->model;
    }
} 