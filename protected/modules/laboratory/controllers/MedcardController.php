<?php

class MedcardController extends LController {

	/**
	 * Render page with medcards
	 */
    public function actionView() {
        $this->render("view");
    }

	/**
	 * That action will load full information about medcard with
	 * patient's addresses
	 *
	 * @in (POST):
	 *  + number - Medcard number
	 * @out (JSON):
	 *  + model - Model with full information about medcard
	 *  + status - True on success
	 *  + [message] - Message with response
	 */
	public function actionLoad() {
		try {
			$row = LMedcard2::model()->fetchInformationLaboratoryLike($this->get("number"));
			if ($row == null) {
				throw new CException("Unresolved medcard number \"{$this->get("number")}\"");
			}
			$this->leave([
				"model" => $row
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Search action, which accepts array with search serialized form
	 * models (LMedcardSearchForm + LSearchRangeForm). That action will
	 * fetch form's values and build search condition form form model
	 * and return Table widget with medcards
	 *
	 * @in (POST):
	 *  + model - Array with serialized forms (string)
	 * @out (JSON):
	 *  + [component] - Component with new rendered table with medcards
	 *  + status - False if form validation failed or true on success
	 */
	public function actionSearch() {
		try {
			if (isset($_POST["mode"])) {
				$mode = $_POST["mode"];
			} else {
				$mode = "mis";
			}
			$like = [];
			$compare = [];
			foreach ($this->getFormModel("model", "post") as $model) {
				foreach ($model->attributes as $key => $value) {
					if (empty($value) || $value == "") {
						continue;
					}
					if ($model->isDropDown($key)) {
						if ($value != -1) {
							$compare[$key] = $value;
						}
					} else {
						$like[$key] = $value;
					}
				}
			}
			$criteria = new CDbCriteria();
			if (isset($like["begin_date"]) && isset($like["end_date"])) {
				$criteria->addBetweenCondition("registration_date", $like["begin_date"], $like["end_date"]);
			}
			unset($like["begin_date"]);
			unset($like["end_date"]);
			foreach ($compare as $key => $value) {
				$criteria->addColumnCondition([
					$key => $value
				]);
			}
			foreach ($like as $key => $value) {
				$criteria->addSearchCondition($key, $value);
			}
			$this->leave([
				"component" => $this->getWidget("MedcardTable", [
					"criteria" => $criteria,
					"mode" => $mode,
				])
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Register some form's values in database, it will automatically
	 * fetch model from $_POST["model"], decode it, build it's FormModel
	 * object and save into database. But you must override
	 * LController::getModel and return instance of controller's model else
	 * it will throw an exception
	 *
	 * @in (POST):
	 *  + model - String with serialized client form via $("form").serialize(), if you're
	 * 		using Modal or Panel widgets that it will automatically find button with
	 * 		submit type and send ajax request
	 * @out (JSON):
	 *  + message - Message with status
	 *  + status - True if everything ok
	 *
	 * @see LController::getModel
	 * @see LModal
	 * @see LPanel
	 */
	public function actionRegister() {
		try {
			$model = $this->getFormModel("model", "post");
			
			$attributes = [];
			LMedcard::model()->insert();
			$this->leave([
				"message" => "Данные медкарты были успешно сохранены"
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
     * Override that method to return controller's model
     * @return ActiveRecord - Controller's model instance
     */
    public function getModel() {
        return new LMedcard();
    }
}