<?php

class MedcardController extends ControllerEx {

	/**
	 * Render page with medcards
	 */
    public function actionNew() {
		if (!$number = Yii::app()->getRequest()->getQuery("n")) {
			$number = LCardNumberGenerator::getGenerator()->generate();
		}
        $this->render("new", [
			"number" => $number
		]);
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
			$row = LMedcardEx::model()->fetchInformationLaboratoryLike($this->get("number"));
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
	 * models (LMedcardSearchForm + LAnalysisSearchForm). That action will
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
			if (!($medcard = Yii::app()->getRequest()->getPost("LMedcardSearchForm"))) {
				throw new CException("Can't resolve search form for medcard \"LMedcardSearchForm\"");
			}
			if (!($analysis = Yii::app()->getRequest()->getPost("LAnalysisSearchForm"))) {
				throw new CException("Can't resolve search form for analysis \"LAnalysisSearchForm\"");
			}
			$criteria = new CDbCriteria();
			if (isset($analysis["begin_date"]) && isset($analysis["end_date"])) {
				$criteria->addBetweenCondition("registration_date", $analysis["begin_date"], $analysis["end_date"]);
			}
			$like = [
				"card_number",
				"fio",
				"phone",
			];
			$compare = [];
			foreach ($medcard as $key => $value) {
				if ($value == -1 || empty($value)) {
					continue;
				}
				if (in_array($key, $like)) {
					$criteria->addSearchCondition($key, $value);
				} else {
					$compare[$key] = $value;
				}
			}
			if (count($compare) > 0) {
				$criteria->addColumnCondition($compare);
			}
			$widget = Yii::app()->getRequest()->getPost("widget");
			$attributes = json_decode(Yii::app()->getRequest()->getPost("attributes"), true);
			if (isset($medcard["fio"]) && !empty($medcard["fio"]) && $widget === "MedcardTable2") {
				$attributes["optimizedPagination"] = true;
			} else {
				$attributes["optimizedPagination"] = false;
			}
			unset($attributes["searchCriteria"]);
			$this->leave([
				"component" => $this->getWidget($widget, [
					"criteria" => $criteria
				] + $attributes)
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * That action will generate card number for laboratory
	 *
	 * @out (JSON):
	 *  + [number] - Just generated card number for laboratory
	 *  + status - Response status, true or false
	 *  + [message] - Message with response
	 *
	 * @throws Exception
	 */
	public function actionGenerate() {
		try {
			$this->leave([
				"message" => "Номер карты был успешно сгенерирован",
				"number" => LCardNumberGenerator::getGenerator()->generate()
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