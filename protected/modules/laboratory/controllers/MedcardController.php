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
			$medcard = $this->requirePost("LMedcardSearchForm");
			$provider = $this->requirePost("provider");
			$config = Yii::app()->getRequest()->getQuery("config", []);
			$criteria = new CDbCriteria();
			$like = [
				"card_number",
				"surname",
				"name",
				"patronymic",
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
			if (!empty($criteria->condition)) {
				$config += [
					"condition" => [
						"condition" => $criteria->condition,
						"params" => $criteria->params,
					]
				];
			}
			$this->leave([
				"component" => $this->getWidget("GridTable", [
					"provider" => new $provider($config)
				])
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
				"message" => "Номер карты сгенерирован",
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