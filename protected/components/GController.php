<?php

abstract class GController extends LController {

	/**
	 * @var string - Guide default layout
	 */
	public $layout = 'application.modules.guides.views.layouts.index';

	/**
	 * Override that method to return name of received form, if returned
	 * value is null, then it receives name from GActiveRecord instance
	 * via getModel method
	 * @see GController::getModel
	 * @return string|null - Name of form or null
	 */
	public function getModelName() {
		return null;
	}

	/**
	 * Override that method to provide some actions after action
	 * @param string $action - Name of action, like 'create', 'update', 'delete'
	 * @param GActiveRecord|array|CActiveRecord $model - Database model instance
	 * @param FormModel $form - Table's form model instance
	 */
	public function after($action, &$model, $form) {
		/* Ignore */
	}

	/**
	 * Default view action, it displays table
	 * with guide rows and all allowed actions
	 */
	public function actionIndex() {
		$this->render("index", [
			"model" => $this->getModel()
		]);
	}

	/**
	 * Override that method to realize your own implementation
	 * of that algorithm with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionCreate() {
		try {
			if (!Yii::app()->getRequest()->getIsPostRequest()) {
				throw new CHttpException(405, "Request method not allowed, requires POST");
			}
			$model = $this->getModel();
			if (!$model instanceof GActiveRecord) {
				throw new CException("Model must be an instance of GActiveRecord class");
			}
			if (($name = $this->getModelName()) == null) {
				$name = get_class($model);
			}
			$name = $name."Form";
			if (!isset($_POST[$name])) {
				throw new CException("Can't receive form's model \"$name\" via POST request method");
			}
			$form = $model->form;
			$post = Yii::app()->getRequest()->getPost($name);
			unset($post["id"]);
			foreach ($post as $key => $value) {
				// @ - fix for default -1 value for reference with foreign key
				if ($value == -1 && $form->getConfig($key)["type"] == "DropDown") {
					$value = null;
				}
				$model->$key = $value;
				$form->$key = $value;
			}
			$model->attributes = $post;
			$form->attributes = $post;
			$this->validateForm($form);
			$model->setAttributes($post);
			$model->unsetAttributes(["id"]);
			if (!$model->save()) {
				$this->leave([
					"message" => "Произошла ошибка при сохранении данных, данные не были сохранены",
					"status" => false
				]);
			}
			print_r($model);
			die;
			$this->after("create", $model, $form);
			$this->leave([
				"message" => "Данные были успешно сохранены"
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Override that method to realize your own implementation
	 * of update method for guides with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionUpdate() {
		if (!Yii::app()->getRequest()->getIsPostRequest()) {
			throw new CHttpException(405, "Request method not allowed, requires POST");
		}
		$model = $this->getModel();
		if (!$model instanceof GActiveRecord) {
			throw new CException("Model must be an instance of GActiveRecord class");
		} else {
			$m = $model;
		}
		$form = $model->form;
		if (($name = $this->getModelName()) == null) {
			$name = get_class($model);
		}
		$name = $name."Form";
		if (!isset($_POST[$name])) {
			throw new CException("Can't receive form's model \"$name\" via POST request method");
		} else {
			$post = Yii::app()->getRequest()->getPost($name);
		}
		$model = $model->findByPk($post["id"]);
		if (!$model) {
			throw new CException("Can't resolve row in \"" .$this->getModelName(). "\" with \"{$post["id"]}\" identification number");
		}
		$post = Yii::app()->getRequest()->getPost($name);
		unset($post["id"]);
		foreach ($post as $key => $value) {
			// @ - fix for default -1 value for reference with foreign key
			if ($value == -1 && $form->getConfig($key)["type"] == "DropDown") {
				$value = null;
			}
			$model->$key = $value;
			$form->$key = $value;
		}
		$model->attributes = $post;
		$form->attributes = $post;
		$this->validateForm($form);
		$model->setAttributes($post);
		if (!$model->save()) {
			$this->leave([
				"message" => "Произошла ошибка при обновлении данных, данные не были обновлены",
				"status" => false
			]);
		}
		$this->after("update", $model, $form);
		$this->leave([
			"message" => "Данные были успешно обновлены"
		]);
	}

	/**
	 * Override that method to realize your own implementation
	 * of guide row deletion with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionDelete() {
		if (!Yii::app()->getRequest()->getIsPostRequest()) {
			throw new CHttpException(405, "Request method not allowed, requires POST");
		}
		if (!$_POST["id"]) {
			throw new CException("Can't resolve component's identification number \"id\" in POST request");
		}
		if (!$this->getModel()->deleteByPk(Yii::app()->getRequest()->getPost("id"))) {
			$this->leave([
				"message" => "Произошла ошибка при удалении данных, данные не были удалены",
				"status" => false
			]);
		}
		$model = [];
		$this->after("delete", $model, null);
		$this->leave([
			"message" => "Данные были успешно удалены"
		]);
	}

	/**
	 * Load information about some model with it's identification number
	 * @throws Exception
	 */
	public function actionLoad() {
		try {
			if (!isset($_GET["id"])) {
				throw new CException("Can't resolve component's identification number \"id\" in POST request");
			}
			$model = $this->getModel();
			if (!$model instanceof GActiveRecord) {
				throw new CException("Model must be an instance of GActiveRecord class");
			}
			$model = $model->findByPk($_GET["id"]);
			if (!$model) {
				throw new CException("Can't resolve row in \"" .$this->getModelName(). "\" with \"{$_GET["id"]}\" identification number");
			}
			$result = $model->getAttributes();
			$this->after("load", $result, null);
			$this->leave([
				"model" => $result
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Update clef sequence, that locks table from
	 * another module
	 * @throws Exception
	 */
	public function actionClef() {
		try {
			if (isset($_POST["FormModelAdapter"])) {
				$post = Yii::app()->getRequest()->getPost("FormModelAdapter");
				if (isset($post["keys"])) {
					$keys = $post["keys"];
				} else {
					$keys = [];
				}
			} else {
				$keys = [];
			}
			if (($clef = $this->getClefTable()) == null) {
				throw new CException("You must override [getClefTable] method to return clef configuration");
			}
			$rows = Yii::app()->getDb()->createCommand()
				->select($clef["key"])
				->from($clef["table"])
				->queryAll();
			$rows = ActiveRecord::getIds($rows, $clef["key"]);
			$save = [];
			foreach ($keys as $key) {
				if (!self::arrayInDrop([ "id" => $key ], $rows)) {
					$save[] = $key;
				}
			}
			foreach ($rows as $key) {
				Yii::app()->getDb()->createCommand()
					->delete($clef["table"], "{$clef["key"]} = :id", [
						":id" => $key
					]);
			}
			foreach ($save as $key) {
				Yii::app()->getDb()->createCommand()
					->insert($clef["table"], [
						$clef["key"] => $key
					]);
			}
			$this->leave([
				"message" => "Данные успешно сохранены"
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Render grid (for future ajax requests)
	 * @throws Exception
	 */
	public function actionGrid() {
		try {
			if (!($model = $this->getModel()) instanceof GActiveRecord) {
				throw new CException("Model must be an instance of GActiveRecord class");
			}
			$model->setScenario("search");
			$model->unsetAttributes();
			$model->attributes = Yii::app()->getRequest()
				->getQuery(get_class($model), []);
			$params = [
				"model" => $model
			];
			if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
				$this->render("index", $params);
			} else {
				$this->widget("GFastGridView", [
					"model" => $model
				]);
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Override that method to return name of table with
	 * clef locked to automate key saving
	 *  + table - Name of clef table
	 *  + key - Name of column with key in table
	 * @return array - Array with clef configuration
	 */
	public function getClefTable() {
		return null;
	}

	/**
	 * Default view action, it displays table
	 * with guide rows and all allowed actions
	 */
	public function actionView() {
		$this->actionIndex();
	}

	/**
	 * Terminate script execution with some json response
	 * @param array $result - Json response
	 */
	public function leave(array $result = []) {
		print json_encode($result + [
				"status" => true
			]);
		Yii::app()->end();
	}
}